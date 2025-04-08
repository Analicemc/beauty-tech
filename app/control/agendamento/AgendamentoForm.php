<?php

//<fileHeader>

//</fileHeader>

use Adianti\Database\TTransaction;

class AgendamentoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'meusalao';
    private static $activeRecord = 'Agendamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_AgendamentoForm';

    //<classProperties>

    //</classProperties>

    use BuilderMasterDetailFieldListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct($param)
    {
        parent::__construct();

        if (!empty($param['target_container'])) {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de agendamento");

        $criteria_pessoa_id = new TCriteria();
        $criteria_estado_agendamento_id = new TCriteria();
        $criteria_procedimento_agendamento_agendamento_procedimento_id = new TCriteria();
        $criteria_procedimento_agendamento_agendamento_procedimento_id->add(new TFilter('ativo', '=', 'T'));

        $id = new THidden('id');
        $previous_class = new THidden('previous_class');
        $agendamento_origem_id = new THidden('agendamento_origem_id');
        $pessoa_id = new TDBCombo('pessoa_id', 'meusalao', 'Pessoa', 'id', '{nome}', 'nome asc', $criteria_pessoa_id);
        $estado_agendamento_id = new TDBCombo('estado_agendamento_id', 'meusalao', 'EstadoAgendamento', 'id', '{nome}', 'nome asc', $criteria_estado_agendamento_id);
        $procedimento_agendamento_agendamento_id = new THidden('procedimento_agendamento_agendamento_id[]');
        $procedimento_agendamento_agendamento___row__id = new THidden('procedimento_agendamento_agendamento___row__id[]');
        $procedimento_agendamento_agendamento___row__data = new THidden('procedimento_agendamento_agendamento___row__data[]');
        $procedimento_agendamento_agendamento_procedimento_id = new TDBCombo('procedimento_agendamento_agendamento_procedimento_id[]', 'meusalao', 'Procedimento', 'id', '{nome}', 'nome asc', $criteria_procedimento_agendamento_agendamento_procedimento_id);
        $procedimento_agendamento_agendamento_procedimento_tempo_estimado = new TEntry('procedimento_agendamento_agendamento_procedimento_tempo_estimado[]');
        $procedimento_agendamento_agendamento_qtde = new TNumeric('procedimento_agendamento_agendamento_qtde[]', '0', ',', '');
        $procedimento_agendamento_agendamento_valor = new TNumeric('procedimento_agendamento_agendamento_valor[]', '2', ',', '.');
        $procedimento_agendamento_agendamento_valor_total = new TNumeric('procedimento_agendamento_agendamento_valor_total[]', '2', ',', '.');
        $this->fieldList_67e09919f78aa = new TFieldList();
        $inicio = new TDateTime('inicio');
        $fim = new TDateTime('fim');
        $obs = new TText('obs');

        $this->fieldList_67e09919f78aa->addField(null, $procedimento_agendamento_agendamento_id, []);
        $this->fieldList_67e09919f78aa->addField(null, $procedimento_agendamento_agendamento___row__id, ['uniqid' => true]);
        $this->fieldList_67e09919f78aa->addField(null, $procedimento_agendamento_agendamento___row__data, []);
        $this->fieldList_67e09919f78aa->addField(new TLabel("Procedimento", null, '14px', null), $procedimento_agendamento_agendamento_procedimento_id, ['width' => '40%']);
        $this->fieldList_67e09919f78aa->addField(new TLabel("Tempo estimado", null, '14px', null), $procedimento_agendamento_agendamento_procedimento_tempo_estimado, ['width' => '10%']);
        $this->fieldList_67e09919f78aa->addField(new TLabel("Quantidade", null, '14px', null), $procedimento_agendamento_agendamento_qtde, ['width' => '10%']);
        $this->fieldList_67e09919f78aa->addField(new TLabel("Valor un.", null, '14px', null), $procedimento_agendamento_agendamento_valor, ['width' => '20%']);
        $this->fieldList_67e09919f78aa->addField(new TLabel("Valor total", null, '14px', null), $procedimento_agendamento_agendamento_valor_total, ['width' => '20%']);

        $this->fieldList_67e09919f78aa->width = '100%';
        $this->fieldList_67e09919f78aa->setFieldPrefix('procedimento_agendamento_agendamento');
        $this->fieldList_67e09919f78aa->name = 'fieldList_67e09919f78aa';

        $this->criteria_fieldList_67e09919f78aa = new TCriteria();
        $this->default_item_fieldList_67e09919f78aa = new stdClass();

        $this->form->addField($procedimento_agendamento_agendamento_id);
        $this->form->addField($procedimento_agendamento_agendamento___row__id);
        $this->form->addField($procedimento_agendamento_agendamento___row__data);
        $this->form->addField($procedimento_agendamento_agendamento_procedimento_id);
        $this->form->addField($procedimento_agendamento_agendamento_procedimento_tempo_estimado);
        $this->form->addField($procedimento_agendamento_agendamento_qtde);
        $this->form->addField($procedimento_agendamento_agendamento_valor);
        $this->form->addField($procedimento_agendamento_agendamento_valor_total);

        $this->fieldList_67e09919f78aa->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $procedimento_agendamento_agendamento_procedimento_id->setChangeAction(new TAction([$this, 'onChangeProcedimento']));

        $procedimento_agendamento_agendamento_qtde->setExitAction(new TAction([$this, 'onChangeQtde']));

        $pessoa_id->addValidation("Cliente", new TRequiredValidator());
        $estado_agendamento_id->addValidation("Status", new TRequiredValidator());
        $inicio->addValidation("Data/hora de início", new TRequiredValidator());
        $fim->addValidation("Data/Hora fim", new TRequiredValidator());
        $procedimento_agendamento_agendamento_procedimento_id->addValidation("Procedimento id", new TRequiredListValidator());
        $procedimento_agendamento_agendamento_valor->addValidation("Valor", new TRequiredListValidator());
        $procedimento_agendamento_agendamento_valor_total->addValidation("Valor total", new TRequiredListValidator());

        $procedimento_agendamento_agendamento_qtde->setAllowNegative(false);
        $procedimento_agendamento_agendamento_valor->setAllowNegative(false);

        $fim->setMask('dd/mm/yyyy hh:ii');
        $inicio->setMask('dd/mm/yyyy hh:ii');

        $fim->setDatabaseMask('yyyy-mm-dd hh:ii');
        $inicio->setDatabaseMask('yyyy-mm-dd hh:ii');

        $pessoa_id->enableSearch();
        $estado_agendamento_id->enableSearch();
        $procedimento_agendamento_agendamento_procedimento_id->enableSearch();

        $procedimento_agendamento_agendamento_valor->setEditable(false);
        $procedimento_agendamento_agendamento_valor_total->setEditable(false);
        $procedimento_agendamento_agendamento_procedimento_tempo_estimado->setEditable(false);

        $id->setValue($param["key"] ?? "");
        $previous_class->setValue($param['previous_class'] ?? null);
        $inicio->setValue($param['inicio'] ?? date('d/m/Y H:i'));
        $procedimento_agendamento_agendamento_qtde->setValue(1);
        $agendamento_origem_id->setValue($param["agendamento_origem_id"] ?? "");

        $id->setSize(200);
        $fim->setSize(170);
        $inicio->setSize(170);
        $obs->setSize('100%', 70);
        $pessoa_id->setSize('100%');
        $agendamento_origem_id->setSize(200);
        $estado_agendamento_id->setSize('100%');
        $procedimento_agendamento_agendamento_qtde->setSize('100%');
        $procedimento_agendamento_agendamento_valor->setSize('100%');
        $procedimento_agendamento_agendamento_valor_total->setSize('100%');
        $procedimento_agendamento_agendamento_procedimento_id->setSize('100%');
        $procedimento_agendamento_agendamento_procedimento_tempo_estimado->setSize('100%');

        $row1 = $this->form->addFields([$id, $previous_class, $agendamento_origem_id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Cliente <span class='mand-field'>*</span>", null, '14px', null, '100%'), $pessoa_id], [new TLabel("Status <span class='mand-field'>*</span>", null, '14px', null, '100%'), $estado_agendamento_id]);
        $row2->layout = [' col-sm-4', ' col-sm-4'];

        $row3 = $this->form->addFields([$this->fieldList_67e09919f78aa]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Data/hora de início <span class='mand-field'>*</span>", null, '14px', null, '100%'), $inicio], [new TLabel("Data/Hora fim <span class='mand-field'>*</span>", null, '14px', null, '100%'), $fim]);
        $row4->layout = [' col-sm-3', ' col-sm-3'];

        $row5 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'), $obs]);
        $row5->layout = [' col-sm-12'];

        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'], ['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary');

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        //<onAfterPageCreation>

        //</onAfterPageCreation>

        parent::add($this->form);
    }

    //<generated-exitAction-onChangeQtde>
    public static function onChangeQtde($param = null)
    {
        try {
            self::onChangeProcedimento($param);
            //</autoCode>
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    //</generated-exitAction-onChangeQtde>

    //<generated-changeAction-onChangeProcedimento>
    public static function onChangeProcedimento($param = null)
    {
        try {
            $keys = $param['procedimento_agendamento_agendamento_procedimento_id'] ?? null;
            if (is_array($keys) && count($keys) > 0) {
                $obj = new stdClass;
                $valores = [];
                $totais = [];
                $estimas = [];
                $tempoEstimadoHoras = 0;
                $campoQtde = 'procedimento_agendamento_agendamento_qtde';

                foreach ($keys as $key => $value) {
                    $qtde = ((int) ($param[$campoQtde][$key]) ?? 1);
                    $proced = Procedimento::findInTransaction('meusalao', $value);
                    if(!$proced)
                        continue;
                    $tempoEstimadoHoras += ($proced->tempo_estimado ?? 0) * $qtde;
                    $valores[$key] = number_format($proced->valor ?? 0, 2, ',', '.');
                    $totais[$key] = number_format($proced->valor * $qtde, 2, ',', '.');
                    $estimas[$key] = $proced->tempo_estimado . 'h';
                }

                $obj->procedimento_agendamento_agendamento_valor = $valores;
                $obj->procedimento_agendamento_agendamento_valor_total = $totais;
                $inicio = DateTime::createFromFormat('d/m/Y H:i', $param['inicio'] ?? date('d/m/Y H:i'));
                $obj->fim = $inicio->add(new DateInterval('PT' . $tempoEstimadoHoras . 'H'))->format('d/m/Y H:i');
                $obj->procedimento_agendamento_agendamento_procedimento_tempo_estimado = $estimas;

                TForm::sendData(self::$formName, $obj);
            }

            //</autoCode>
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    //</generated-changeAction-onChangeProcedimento>

    //<generated-FormAction-onSave>
    public function onSave($param = null)
    {
        try {
            TTransaction::open(self::$database);
            $messageAction = null;

            $this->form->validate();

            $object = new Agendamento();

            $data = $this->form->getData();
            $object->fromArray((array) $data);

            $object->store(); // save the object //</blockLine>

            //</afterStoreAutoCode> //</blockLine>

            //<fieldList-2505469-20379006> //</hideLine>
            $procedimento_agendamento_agendamento_items = $this->storeItems('ProcedimentoAgendamento', 'agendamento_id', $object, $this->fieldList_67e09919f78aa, function ($masterObject, $detailObject) { //</blockLine>

                $proced = $detailObject->get_procedimento();
                $detailObject->valor_total = ($detailObject->qtde ?? 0) * ($detailObject->valor ?? 0);

                //</autoCode>
            }, $this->criteria_fieldList_67e09919f78aa); //</blockLine>
            //</hideLine> //</fieldList-2505469-20379006>

            $data->id = $object->id; //</blockLine>

            $this->form->setData($data);

            //</messageAutoCode> //</blockLine>
            //<generatedAutoCode>
            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            //</generatedAutoCode>

            if ($object->estado_agendamento->final != 'T') {
                TScript::create("Template.closeRightPanel();");
                TApplication::loadPage($param['previous_class'] ?? 'AgendamentoList', 'onShow');
                TForm::sendData(self::$formName, (object)['id' => $object->id]);
            }
            /*TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);*/
            TTransaction::close();
        } catch (Exception $e) {
            //</catchAutoCode> //</blockLine>

            new TMessage('error', $e->getMessage());
            $this->form->setData($this->form->getData());
            TTransaction::rollback();
        }
    }
    //</generated-FormAction-onSave>

    //<generated-onEdit>
    public function onEdit($param) //</ini>
    {
        try {
            if (isset($param['key'])) {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Agendamento($key); // instantiates the Active Record //</blockLine>

                //</beforeSetDataAutoCode> //</blockLine> 

                //<fieldList-2505469-20379006> //</hideLine>
                $this->fieldList_67e09919f78aa_items = $this->loadItems('ProcedimentoAgendamento', 'agendamento_id', $object, $this->fieldList_67e09919f78aa, function ($masterObject, $detailObject, $objectItems) { //</blockLine>

                    //code here

                    //</autoCode>
                }, $this->criteria_fieldList_67e09919f78aa); //</blockLine>
                //</hideLine> //</fieldList-2505469-20379006>

                $this->form->setData($object); // fill the form //</blockLine>

                //</afterSetDataAutoCode> //</blockLine>
                //<generatedAutoCode>

                //</generatedAutoCode>

                TTransaction::close(); // close the transaction 
            } else {
                $this->form->clear();
            }
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    } //</end>
    //</generated-onEdit>

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear($param)
    {
        $this->form->clear(true);

        $this->fieldList_67e09919f78aa->addHeader();
        $this->fieldList_67e09919f78aa->addDetail($this->default_item_fieldList_67e09919f78aa);

        $this->fieldList_67e09919f78aa->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //<onFormClear>

        //</onFormClear>

    }

    public function onShow($param = null)
    {
        $this->fieldList_67e09919f78aa->addHeader();
        $this->fieldList_67e09919f78aa->addDetail($this->default_item_fieldList_67e09919f78aa);

        $this->fieldList_67e09919f78aa->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //<onShow>

        //</onShow>
    }

    public static function getFormName()
    {
        return self::$formName;
    }

    //</hideLine> <addUserFunctionsCode/>

    //<userCustomFunctions>

    //</userCustomFunctions>

}
