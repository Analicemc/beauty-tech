<?php

//<fileHeader>

//</fileHeader>

use Adianti\Database\TTransaction;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Template\THtmlRenderer;
class DashboardFinanceiro extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = '';
    private static $activeRecord = '';
    private static $primaryKey = '';
    private static $formName = 'form_DashboardFinanceiro';

    //<classProperties>

    //</classProperties>

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if (!empty($param['target_container'])) {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Dashboard");

        $criteria_previsto_a_receber = new TCriteria();
        $criteria_a_receber = new TCriteria();
        $criteria_recebido = new TCriteria();
        $criteria_previsto_a_pagar = new TCriteria();
        $criteria_a_pagar = new TCriteria();
        $criteria_pago = new TCriteria();
        $criteria_previsto_receber_categoria = new TCriteria();
        $criteria_previsto_pagar_categoria = new TCriteria();
        $criteria_previsto_receber_mes = new TCriteria();

        $filterVar = TipoConta::RECEBER;
        $criteria_previsto_a_receber->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = TipoConta::RECEBER;
        $criteria_a_receber->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = NULL;
        $criteria_a_receber->add(new TFilter('conta.dt_pagamento', 'is', $filterVar));
        $filterVar = TipoConta::RECEBER;
        $criteria_recebido->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = NULL;
        $criteria_recebido->add(new TFilter('conta.dt_pagamento', 'is not', $filterVar));
        $filterVar = TipoConta::PAGAR;
        $criteria_previsto_a_pagar->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = TipoConta::PAGAR;
        $criteria_a_pagar->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = NULL;
        $criteria_a_pagar->add(new TFilter('conta.dt_pagamento', 'is', $filterVar));
        $filterVar = TipoConta::PAGAR;
        $criteria_pago->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = NULL;
        $criteria_pago->add(new TFilter('conta.dt_pagamento', 'is not', $filterVar));
        $filterVar = TipoConta::RECEBER;
        $criteria_previsto_receber_categoria->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));
        $filterVar = TipoConta::PAGAR;
        $criteria_previsto_pagar_categoria->add(new TFilter('conta.tipo_conta_id', '=', $filterVar));

        $mes = new TCombo('mes');
        $ano = new TCombo('ano');
        $button_buscar = new TButton('button_buscar');
        $previsto_a_receber = new BIndicator('previsto_a_receber');
        $a_receber = new BIndicator('a_receber');
        $recebido = new BIndicator('recebido');
        $previsto_a_pagar = new BIndicator('previsto_a_pagar');
        $a_pagar = new BIndicator('a_pagar');
        $pago = new BIndicator('pago');
        $previsto_receber_categoria = new BDonutChart('previsto_receber_categoria');
        $previsto_pagar_categoria = new BDonutChart('previsto_pagar_categoria');
        $previsto_receber_mes = new BLineChart('previsto_receber_mes');

        $button_buscar->setAction(new TAction(['DashboardFinanceiro', 'onShow']), "Buscar");
        $button_buscar->addStyleClass('btn-primary');
        $button_buscar->setImage('fas:search #FFFFFF');
        $mes->setSize('100%');
        $ano->setSize('100%');

        $ano->addItems(TempoService::getAnos());
        $mes->addItems(TempoService::getMeses());

        $mes->setValue($param['mes'] ?? date('m'));
        $ano->setValue($param['ano'] ?? date('Y'));

        $mes->enableSearch();
        $ano->enableSearch();

        $previsto_a_receber->setDatabase('meusalao');
        $previsto_a_receber->setFieldValue("conta.valor");
        $previsto_a_receber->setModel('Conta');
        $previsto_a_receber->setTransformerValue(function ($value) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $previsto_a_receber->setTotal('sum');
        $previsto_a_receber->setColors('#2ECC71', '#ffffff', '#27AE60', '#ffffff');
        $previsto_a_receber->setTitle("previsto à receber", '#ffffff', '20', '');
        $criteria_previsto_a_receber->add(new TFilter('conta.deleted_at', 'is', NULL));
        $previsto_a_receber->setCriteria($criteria_previsto_a_receber);
        $previsto_a_receber->setIcon(new TImage('fas:shopping-basket #ffffff'));
        $previsto_a_receber->setValueSize("20");
        $previsto_a_receber->setValueColor("#ffffff", 'B');
        $previsto_a_receber->setSize('100%', 95);
        $previsto_a_receber->setLayout('horizontal', 'left');

        $a_receber->setDatabase('meusalao');
        $a_receber->setFieldValue("conta.valor");
        $a_receber->setModel('Conta');
        $a_receber->setTransformerValue(function ($value) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $a_receber->setTotal('sum');
        $a_receber->setColors('#16A085', '#FFFFFF', '#1ABC9C', '#FFFFFF');
        $a_receber->setTitle("à receber", '#FFFFFF', '20', '');
        $criteria_a_receber->add(new TFilter('conta.deleted_at', 'is', NULL));
        $a_receber->setCriteria($criteria_a_receber);
        $a_receber->setIcon(new TImage('fas:shopping-basket #FFFFFF'));
        $a_receber->setValueSize("20");
        $a_receber->setValueColor("#FFFFFF", 'B');
        $a_receber->setSize('100%', 95);
        $a_receber->setLayout('horizontal', 'left');

        $recebido->setDatabase('meusalao');
        $recebido->setFieldValue("conta.valor");
        $recebido->setModel('Conta');
        $recebido->setTransformerValue(function ($value) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $recebido->setTotal('sum');
        $recebido->setColors('#3498DB', '#FFFFFF', '#2980B9', '#FFFFFF');
        $recebido->setTitle("RECEBIDO", '#FFFFFF', '20', '');
        $criteria_recebido->add(new TFilter('conta.deleted_at', 'is', NULL));
        $recebido->setCriteria($criteria_recebido);
        $recebido->setIcon(new TImage('fas:shopping-basket #FFFFFF'));
        $recebido->setValueSize("20");
        $recebido->setValueColor("#FFFFFF", 'B');
        $recebido->setSize('100%', 95);
        $recebido->setLayout('horizontal', 'left');

        $previsto_a_pagar->setDatabase('meusalao');
        $previsto_a_pagar->setFieldValue("conta.valor");
        $previsto_a_pagar->setModel('Conta');
        $previsto_a_pagar->setTransformerValue(function ($value) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $previsto_a_pagar->setTotal('sum');
        $previsto_a_pagar->setColors('#E74C3C', '#FFFFFF', '#C0392B', '#FFFFFF');
        $previsto_a_pagar->setTitle("previsto à pagar", '#FFFFFF', '20', '');
        $criteria_previsto_a_pagar->add(new TFilter('conta.deleted_at', 'is', NULL));
        $previsto_a_pagar->setCriteria($criteria_previsto_a_pagar);
        $previsto_a_pagar->setIcon(new TImage('fas:shopping-basket #FFFFFF'));
        $previsto_a_pagar->setValueSize("20");
        $previsto_a_pagar->setValueColor("#FFFFFF", 'B');
        $previsto_a_pagar->setSize('100%', 95);
        $previsto_a_pagar->setLayout('horizontal', 'left');

        $a_pagar->setDatabase('meusalao');
        $a_pagar->setFieldValue("conta.valor");
        $a_pagar->setModel('Conta');
        $a_pagar->setTransformerValue(function ($value) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $a_pagar->setTotal('sum');
        $a_pagar->setColors('#D35400', '#FFFFFF', '#E67E22', '#FFFFFF');
        $a_pagar->setTitle("à pagar", '#FFFFFF', '20', '');
        $criteria_a_pagar->add(new TFilter('conta.deleted_at', 'is', NULL));
        $a_pagar->setCriteria($criteria_a_pagar);
        $a_pagar->setIcon(new TImage('fas:shopping-basket #FFFFFF'));
        $a_pagar->setValueSize("20");
        $a_pagar->setValueColor("#FFFFFF", 'B');
        $a_pagar->setSize('100%', 95);
        $a_pagar->setLayout('horizontal', 'left');

        $pago->setDatabase('meusalao');
        $pago->setFieldValue("conta.valor");
        $pago->setModel('Conta');
        $pago->setTransformerValue(function ($value) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $pago->setTotal('sum');
        $pago->setColors('#F1C40F', '#FFFFFF', '#F39C12', '#FFFFFF');
        $pago->setTitle("PAGO", '#FFFFFF', '20', '');
        $criteria_pago->add(new TFilter('conta.deleted_at', 'is', NULL));
        $pago->setCriteria($criteria_pago);
        $pago->setIcon(new TImage('fas:shopping-basket #FFFFFF'));
        $pago->setValueSize("20");
        $pago->setValueColor("#FFFFFF", 'B');
        $pago->setSize('100%', 95);
        $pago->setLayout('horizontal', 'left');

        $previsto_receber_categoria->setDatabase('meusalao');
        $previsto_receber_categoria->setFieldValue("conta.valor");
        $previsto_receber_categoria->setFieldGroup("categoria.nome");
        $previsto_receber_categoria->setModel('Conta');
        $previsto_receber_categoria->setTitle("PREVISTO À RECEBER POR CATEGORIA");
        $previsto_receber_categoria->setTransformerValue(function ($value, $row, $data) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $previsto_receber_categoria->setJoins([
            'categoria' => ['conta.categoria_id', 'categoria.id']
        ]);
        $previsto_receber_categoria->setTotal('sum');
        $previsto_receber_categoria->showLegend(true);
        $previsto_receber_categoria->enableOrderByValue('asc');
        $criteria_previsto_receber_categoria->add(new TFilter('conta.deleted_at', 'is', NULL));
        $previsto_receber_categoria->setCriteria($criteria_previsto_receber_categoria);
        $previsto_receber_categoria->setSize('100%', 280);
        $previsto_receber_categoria->disableZoom();

        $previsto_pagar_categoria->setDatabase('meusalao');
        $previsto_pagar_categoria->setFieldValue("conta.valor");
        $previsto_pagar_categoria->setFieldGroup("categoria.nome");
        $previsto_pagar_categoria->setModel('Conta');
        $previsto_pagar_categoria->setTitle("PREVISTO À PAGAR POR CATEGORIA");
        $previsto_pagar_categoria->setTransformerValue(function ($value, $row, $data) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $previsto_pagar_categoria->setJoins([
            'categoria' => ['conta.categoria_id', 'categoria.id']
        ]);
        $previsto_pagar_categoria->setTotal('sum');
        $previsto_pagar_categoria->showLegend(true);
        $previsto_pagar_categoria->enableOrderByValue('asc');
        $criteria_previsto_pagar_categoria->add(new TFilter('conta.deleted_at', 'is', NULL));
        $previsto_pagar_categoria->setCriteria($criteria_previsto_pagar_categoria);
        $previsto_pagar_categoria->setSize('100%', 280);
        $previsto_pagar_categoria->disableZoom();

        $previsto_receber_mes->setDatabase('meusalao');
        $previsto_receber_mes->setFieldValue("conta.valor");
        $previsto_receber_mes->setFieldGroup(["conta.ano_mes_vencimento"]);
        $previsto_receber_mes->setModel('Conta');
        $previsto_receber_mes->setTitle("PREVISTO À RECEBER POR MÊS");
        $previsto_receber_mes->setTransformerLegend(function ($value, $row, $data) {

            // 202009
            // 09/2022
            return substr($value, 4, 2) . '/' . substr($value, 0, 4);
        });
        $previsto_receber_mes->setTransformerValue(function ($value, $row, $data) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });
        $previsto_receber_mes->setTotal('sum');
        $previsto_receber_mes->showLegend(true);
        $criteria_previsto_receber_mes->add(new TFilter('conta.deleted_at', 'is', NULL));
        $previsto_receber_mes->setCriteria($criteria_previsto_receber_mes);
        $previsto_receber_mes->setLabelValue("Valor Total");
        $previsto_receber_mes->setSize('100%', 280);
        $previsto_receber_mes->showArea(false);
        $previsto_receber_mes->disableZoom();

        $row1 = $this->form->addFields([new TLabel("Mês:", null, '14px', null, '100%'), $mes], [new TLabel("Ano:", null, '14px', null), $ano], [new TLabel(" ", null, '14px', null, '100%'), $button_buscar]);
        $row1->layout = [' col-sm-2', ' col-sm-2', 'col-sm-2'];

        $row2 = $this->form->addFields([$previsto_a_receber], [$a_receber], [$recebido]);
        $row2->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        $row3 = $this->form->addFields([$previsto_a_pagar], [$a_pagar], [$pago]);
        $row3->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        /*$row4 = $this->form->addFields([$previsto_receber_categoria], [$previsto_pagar_categoria]);
        $row4->layout = [' col-sm-6', 'col-sm-6'];

        $row5 = $this->form->addFields([$previsto_receber_mes]);
        $row5->layout = [' col-sm-12'];*/

        if (!isset($param['mes']) && $mes->getValue()) {
            $_POST['mes'] = $mes->getValue();
        }
        if (!isset($param['ano']) && $ano->getValue()) {
            $_POST['ano'] = $ano->getValue();
        }

        $searchData = $this->form->getData();
        $this->form->setData($searchData);

        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_previsto_a_receber->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_previsto_a_receber->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_a_receber->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_a_receber->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_recebido->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_recebido->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_previsto_a_pagar->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_previsto_a_pagar->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_a_pagar->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_a_pagar->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_pago->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_pago->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_previsto_receber_categoria->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_previsto_receber_categoria->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->mes;
        if ($filterVar) {
            $criteria_previsto_pagar_categoria->add(new TFilter('conta.mes_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_previsto_pagar_categoria->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }
        $filterVar = $searchData->ano;
        if ($filterVar) {
            $criteria_previsto_receber_mes->add(new TFilter('conta.ano_vencimento', '=', $filterVar));
        }

        BChart::generate($previsto_a_receber, $a_receber, $recebido, $previsto_a_pagar, $a_pagar, $pago/*, $previsto_receber_categoria, $previsto_pagar_categoria, $previsto_receber_mes*/);
        TTransaction::open('meusalao');
        // TTransaction::dump();
        $criteria_previsto_receber_categoria->setProperty('order', 'categoria_id');
        $contas_receber_categoria = Conta::getObjects($criteria_previsto_receber_categoria);
        $arr = [];
        $arr[] = ['Categoria', 'Valor'];

        foreach($contas_receber_categoria as $conta) {
            $arr[] = [$conta->categoria->nome, $conta->valor];
        }
        
        $html = new THtmlRenderer('app/resources/google_pie_chart.html');
        $html->enableSection('main', array('data' => json_encode($arr),
                                                                    'width'  => '100%',
                                                                    'height'  => '300px',
                                                                    'title'  => 'Contas a receber por categoria',
                                                                    'ytitle' => 'Categoria', 
                                                                    'xtitle' => 'Valor',
                                                                    'uniqid' => uniqid()));

        $criteria_previsto_pagar_categoria->setProperty('order', 'categoria_id');
        $contas_pagar_categoria = Conta::getObjects($criteria_previsto_pagar_categoria);
        $arr = [];
        $arr[] = ['Categoria', 'Valor'];
        foreach($contas_pagar_categoria as $conta) {
            $arr[] = [$conta->categoria->nome, $conta->valor];
        }
        $html_pagar = new THtmlRenderer('app/resources/google_pie_chart.html');
        $html_pagar->enableSection('main', array('data' => json_encode($arr),
                                                                            'width'  => '100%',
                                                                            'height'  => '300px',
                                                                            'title'  => 'Contas a pagar por categoria',
                                                                            'ytitle' => 'Categoria', 
                                                                            'xtitle' => 'Valor',
                                                                            'uniqid' => uniqid()));
        $arr = [];
        $arr[] = [ 'Mês', 'Valor'];
        $meses = TempoService::getMesesSemZeros();
        $criteria_previsto_receber_mes->add(new TFilter('tipo_conta_id', '=', TipoConta::RECEBER));
        $criteria_previsto_receber_mes->setProperty('order', 'mes_vencimento asc');
        $contas_previsto_receber_mes = Conta::getObjects($criteria_previsto_receber_mes);
        $contas_somadas = [];
        foreach($contas_previsto_receber_mes as $conta) {
            if(!isset($contas_somadas[$conta->mes_vencimento])) {
                $contas_somadas[$meses[$conta->mes_vencimento]] = 0;
            }
            $contas_somadas[$meses[$conta->mes_vencimento]] += $conta->valor;
        }
        foreach($contas_somadas as $key => $value) {
            $arr[] = [$key, $value];
        }
        $html_previsto_receber = new THtmlRenderer('app/resources/google_line_chart.html');
        $html_previsto_receber->enableSection('main', array('data'   => json_encode($arr),
                                                                                        'width'  => '100%',
                                                                                        'height'  => '300px',
                                                                                        'title'  => 'Previsto a receber por mês',
                                                                                        'ytitle' => 'Valor', 
                                                                                        'xtitle' => 'Mês',
                                                                                        'uniqid' => uniqid()));
        TTransaction::close();
        
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        $container->add($this->form);
        $container->add($html);
        $container->add($html_pagar);
        $container->add(new TLabel("<h4>Previsto a receber por mês</h4>", '#333', '14', 'B'));
        $container->add($html_previsto_receber);

        parent::add($container);
    }

    public function onShow($param = null)
    {

    }

}
