<?php

use Adianti\Database\TTransaction;

/**
 * FullCalendarStaticView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    https://adiantiframework.com.br/license-tutor
 */

class AgendamentoCalendarFormView extends TPage
{
    private $fc;

    public function __construct()
    {
        parent::__construct();
        $this->fc = new TFullCalendar(date('Y-m-d'), 'agendaWeek');
        $this->fc->setTimeRange('08:00:00', '20:00:00');
        $this->fc->enableFullHeight();
        $this->fc->enablePopover('Title {title}', '<b>{title}</b><br><i class="fa fa-user"></i> {person}<br>{description}');

        $this->loadAgendamentos();

        $this->fc->setDayClickAction(new TAction([$this, 'onDayClick']));
        $this->fc->setEventClickAction(new TAction([$this, 'onEventClick']));
        $this->fc->setEventUpdateAction(new TAction([$this, 'onUpdateEvent']));

        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(TPanelGroup::pack('', $this->fc));

        parent::add($vbox);
    }

    private function loadAgendamentos()
    {
        try {
            TTransaction::open('meusalao');
            $repository = new TRepository('Agendamento');
            $criteria = new TCriteria();

            $agendamentos = $repository->load($criteria);
            foreach ($agendamentos as $agendamento) {
                $pessoa = new Pessoa($agendamento->pessoa_id);

                $obj = (object) [
                    'title'       => "Agendamento #{$agendamento->id}",
                    'person'      => $pessoa->nome,
                    'description' => $agendamento->get_procedimento_agendamento_agendamento_to_string() ?? 'Sem descrição'
                ];

                $this->fc->addEvent(
                    $agendamento->id,
                    "Agendamento #{$agendamento->id}",
                    $agendamento->inicio,
                    $agendamento->fim,
                    null,
                    '#5AB34B',
                    $obj
                );
            }

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public static function onDayClick($param)
    {
        TApplication::loadPage('AgendamentoForm', 'onShow', ['previous_class' => __CLASS__, 'inicio' => $param['date']]);
    }

    public static function onEventClick($param)
    {
        TApplication::loadPage('AgendamentoForm', 'onEdit', ['key' => $param['key'], 'previous_class' => __CLASS__]);
    }

    public static function onUpdateEvent($param)
    {
        try {
            TTransaction::open('meusalao');
            $agend = Agendamento::find($param['key']);
            $agend->inicio = $param['start_time'];
            $agend->fim = $param['end_time'];
            $agend->store();
            TTransaction::close();
            TToast::show('success', 'Agendamento atualizado com sucesso', 'top right', 'fas:check-circle');
        } catch (Exception $th) {
            TTransaction::rollback();
            TToast::show('error', 'Erro ao atualizar agendamento: ' . $th->getMessage(), 'top right', 'fas:times-circle');
        }
    }
}
