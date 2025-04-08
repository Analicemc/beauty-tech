<?php

//<fileHeader>

//</fileHeader>

class AgendamentoContasForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'meusalao';
    private static $activeRecord = 'Agendamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_AgendamentoContasForm';

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
        $this->form->setFormTitle("Gerar contas a partir de agendamento");

        $criteria_forma_pagamento_id = new TCriteria();
        $criteria_categoria_id = new TCriteria();
        $criteria_conta_agendamento_forma_pagamento_id = new TCriteria();

        $filterVar = TipoConta::RECEBER;
        $criteria_categoria_id->add(new TFilter('tipo_conta_id', '=', $filterVar));

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $id = new THidden('id');
        $forma_pagamento_id = new TDBCombo('forma_pagamento_id', 'meusalao', 'FormaPagamento', 'id', '{nome}', 'nome asc', $criteria_forma_pagamento_id);
        $categoria_id = new TDBCombo('categoria_id', 'meusalao', 'Categoria', 'id', '{nome}', 'nome asc', $criteria_categoria_id);
        $dt_vencimento = new TDate('dt_vencimento');
        $valor = new TNumeric('valor', '2', ',', '.');
        $intervalo = new TCombo('intervalo');
        $qtde_gerar = new TSpinner('qtde_gerar');
        $button_gerar_contas = new TButton('button_gerar_contas');
        $conta_agendamento_id = new THidden('conta_agendamento_id[]');
        $conta_agendamento___row__id = new THidden('conta_agendamento___row__id[]');
        $conta_agendamento___row__data = new THidden('conta_agendamento___row__data[]');
        $conta_agendamento_forma_pagamento_id = new TDBCombo('conta_agendamento_forma_pagamento_id[]', 'meusalao', 'FormaPagamento', 'id', '{nome}', 'nome asc', $criteria_conta_agendamento_forma_pagamento_id);
        $conta_agendamento_dt_vencimento = new TDate('conta_agendamento_dt_vencimento[]');
        $conta_agendamento_dt_pagamento = new TDate('conta_agendamento_dt_pagamento[]');
        $conta_agendamento_valor = new TNumeric('conta_agendamento_valor[]', '2', ',', '.');
        $conta_agendamento_parcela = new TEntry('conta_agendamento_parcela[]');
        $this->fieldlist_contas = new TFieldList();

        $this->fieldlist_contas->addField(null, $conta_agendamento_id, []);
        $this->fieldlist_contas->addField(null, $conta_agendamento___row__id, ['uniqid' => true]);
        $this->fieldlist_contas->addField(null, $conta_agendamento___row__data, []);
        $this->fieldlist_contas->addField(new TLabel("Forma de pagamento", null, '14px', null), $conta_agendamento_forma_pagamento_id, ['width' => '20%']);
        $this->fieldlist_contas->addField(new TLabel("Data de vencimento", null, '14px', null), $conta_agendamento_dt_vencimento, ['width' => '20%']);
        $this->fieldlist_contas->addField(new TLabel("Data de pagamento", null, '14px', null), $conta_agendamento_dt_pagamento, ['width' => '20%']);
        $this->fieldlist_contas->addField(new TLabel("Valor", null, '14px', null), $conta_agendamento_valor, ['width' => '20%']);
        $this->fieldlist_contas->addField(new TLabel("Parcela", null, '14px', null), $conta_agendamento_parcela, ['width' => '20%']);

        $this->fieldlist_contas->width = '100%';
        $this->fieldlist_contas->setFieldPrefix('conta_agendamento');
        $this->fieldlist_contas->name = 'fieldlist_contas';

        $this->criteria_fieldlist_contas = new TCriteria();
        $this->default_item_fieldlist_contas = new stdClass();

        $this->form->addField($conta_agendamento_id);
        $this->form->addField($conta_agendamento___row__id);
        $this->form->addField($conta_agendamento___row__data);
        $this->form->addField($conta_agendamento_forma_pagamento_id);
        $this->form->addField($conta_agendamento_dt_vencimento);
        $this->form->addField($conta_agendamento_dt_pagamento);
        $this->form->addField($conta_agendamento_valor);
        $this->form->addField($conta_agendamento_parcela);

        $this->fieldlist_contas->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $forma_pagamento_id->addValidation("Forma de pagamento", new TRequiredValidator());
        $categoria_id->addValidation("Categoria", new TRequiredValidator());
        $dt_vencimento->addValidation("Data de vcto inicial", new TRequiredValidator());
        $valor->addValidation("Valor", new TRequiredValidator());
        $conta_agendamento_forma_pagamento_id->addValidation("Forma de pagamento", new TRequiredListValidator());
        $conta_agendamento_valor->addValidation("Valor", new TRequiredListValidator());

        $valor->setAllowNegative(false);
        $intervalo->addItems(["semanal" => "Semanal", "quinzenal" => "Quinzenal", "mensal" => "Mensal"]);
        $qtde_gerar->setRange(1, 24, 1);
        $button_gerar_contas->setAction(new TAction([$this, 'onGerarContas']), "Gerar contas");
        $button_gerar_contas->addStyleClass('btn-default');
        $button_gerar_contas->setImage('fas:cogs #009688');
        $dt_vencimento->setMask('dd/mm/yyyy');
        $conta_agendamento_dt_pagamento->setMask('dd/mm/yyyy');
        $conta_agendamento_dt_vencimento->setMask('dd/mm/yyyy');

        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');
        $conta_agendamento_dt_pagamento->setDatabaseMask('yyyy-mm-dd');
        $conta_agendamento_dt_vencimento->setDatabaseMask('yyyy-mm-dd');

        $qtde_gerar->setValue(1);
        $intervalo->setValue('mensal');
        $dt_vencimento->setValue(date('Y-m-d'));
        $id->setValue($param["agendamento_id"] ?? "");

        $intervalo->enableSearch();
        $categoria_id->enableSearch();
        $forma_pagamento_id->enableSearch();
        $conta_agendamento_forma_pagamento_id->enableSearch();

        $id->setSize(200);
        $valor->setSize('100%');
        $qtde_gerar->setSize(130);
        $intervalo->setSize('100%');
        $dt_vencimento->setSize(130);
        $categoria_id->setSize('100%');
        $forma_pagamento_id->setSize('100%');
        $conta_agendamento_valor->setSize('100%');
        $conta_agendamento_parcela->setSize('100%');
        $conta_agendamento_dt_pagamento->setSize(110);
        $conta_agendamento_dt_vencimento->setSize(110);
        $conta_agendamento_forma_pagamento_id->setSize('100%');

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Forma de pagamento <span class='mand-field'>*</span>", null, '14px', null, '100%'), $forma_pagamento_id], [new TLabel("Categoria <span class='mand-field'>*</span>", null, '14px', null, '100%'), $categoria_id]);
        $row2->layout = [' col-sm-6', ' col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Data de vcto inicial <span class='mand-field'>*</span>", null, '14px', null, '100%'), $dt_vencimento], [new TLabel("Valor <span class='mand-field'>*</span>", null, '14px', null, '100%'), $valor], [new TLabel("Intervalo:", null, '14px', null, '100%'), $intervalo], [new TLabel("Qtde a gerar:", null, '14px', null, '100%'), $qtde_gerar], [$button_gerar_contas]);
        $row3->layout = ['col-sm-3', 'col-sm-3', ' col-sm-3', ' col-sm-3', 'col-sm-2'];

        $row4 = $this->form->addFields([$this->fieldlist_contas]);
        $row4->layout = [' col-sm-12'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // create the form actions
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

        $style = new TStyle('right-panel > .container-part[page-name=AgendamentoContasForm]');
        $style->width = '100% !important';
        $style->show(true);
    }

    //<generated-FormAction-onGerarContas>
    public static function onGerarContas($param = null)
    {
        try {
            (new TRequiredValidator)->validate('Valor', $param['valor']);
            (new TRequiredValidator)->validate('Data vencimento inicial', $param['dt_vencimento']);
            (new TRequiredValidator)->validate('Forma de pagamento', $param['forma_pagamento_id']);
            (new TRequiredValidator)->validate('Categoria', $param['categoria_id']);
            (new TRequiredValidator)->validate('Intervalo', $param['intervalo']);
            (new TRequiredValidator)->validate('Qtde a gerar', $param['qtde_gerar']);

            $valor = (float) str_replace(',', '.', str_replace('.', '', $param['valor']));
            $forma_pagamento_id = $param['forma_pagamento_id'];
            $categoria_id = $param['categoria_id'];
            $dt_vencimento = TDate::date2us($param['dt_vencimento']);
            $qtde_gerar = $param['qtde_gerar'];
            $intervalo = $param['intervalo'];

            $valorParcela = $valor / $qtde_gerar;
            $dt_vencimento = new DateTime($dt_vencimento);

            $data = new stdClass();
            $data->conta_agendamento_forma_pagamento_id = [];
            $data->conta_agendamento_dt_vencimento = [];
            $data->conta_agendamento_dt_pagamento = [];
            $data->conta_agendamento_valor = [];
            $data->conta_agendamento_parcela = [];

            $parcela = 1;
            for ($i = 0; $i < $qtde_gerar; $i++) {
                if ($parcela != 1) {
                    if ($intervalo == 'semanal') {
                        $dt_vencimento->add(new DateInterval("P1W"));
                    } elseif ($intervalo == 'quinzenal') {
                        $dt_vencimento->add(new DateInterval("P15D"));
                    } elseif ($intervalo == 'mensal') {
                        $dt_vencimento->add(new DateInterval("P1M"));
                    }
                }

                $data->conta_parcela[] = $parcela;
                $data->conta_dt_vencimento[] = $dt_vencimento->format('d/m/Y');
                $data->conta_valor[] = number_format($valorParcela, 2, ',', '.');

                $data->conta_agendamento_forma_pagamento_id[] = $forma_pagamento_id;
                $data->conta_agendamento_dt_vencimento[] = $dt_vencimento->format('d/m/Y');
                $data->conta_agendamento_valor[] = number_format($valorParcela, 2, ',', '.');
                $data->conta_agendamento_parcela[] = $parcela;

                $parcela++;
            }

            TFieldList::clearRows('fieldList_contas');
            TFieldList::addRows('fieldList_contas', $qtde_gerar - 1, 1);
            TForm::sendData(self::$formName, $data, false, true, 500);

            //</autoCode>
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    //</generated-FormAction-onGerarContas>

    //<generated-FormAction-onSave>
    public function onSave($param = null)
    {
        try {
            TTransaction::open(self::$database); // open a transaction
            TTransaction::dump();
            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Agendamento(); // create an empty object //</blockLine>

            $data = $this->form->getData(); // get form data as array
            $object->fromArray((array) $data); // load the object with data

            //</beforeStoreAutoCode> //</blockLine>

            $object->store(); // save the object //</blockLine>
            $object->find($object->id);
            //</afterStoreAutoCode> //</blockLine> 

            //<fieldList-2487450-20379122> //</hideLine>
            $conta_agendamento_items = $this->storeItems('Conta', 'agendamento_id', $object, $this->fieldlist_contas, function ($masterObject, $detailObject) { //</blockLine>

                $detailObject->pessoa_id = $masterObject->pessoa_id;
                $detailObject->tipo_conta_id = TipoConta::RECEBER;
                $detailObject->categoria_id = $masterObject->categoria_id;

                //</autoCode>
            }, $this->criteria_fieldlist_contas); //</blockLine>
            //</hideLine> //</fieldList-2487450-20379122>

            $data->id = $object->id; //</blockLine>

            $this->form->setData($data);
            TTransaction::close();

            //</messageAutoCode> //</blockLine>
            //<generatedAutoCode>
            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            //</generatedAutoCode>

            //</endTryAutoCode> //</blockLine>
            //<generatedAutoCode>
            TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

            //</generatedAutoCode>

        } catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> //</blockLine>

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData($this->form->getData()); // keep form data
            TTransaction::rollback(); // undo all pending operations
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

                //<fieldList-2487450-20379122> //</hideLine>
                $this->fieldlist_contas_items = $this->loadItems('Conta', 'agendamento_id', $object, $this->fieldlist_contas, function ($masterObject, $detailObject, $objectItems) { //</blockLine>

                    //code here

                    //</autoCode>
                }, $this->criteria_fieldlist_contas); //</blockLine>
                //</hideLine> //</fieldList-2487450-20379122>

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

        $this->fieldlist_contas->addHeader();
        $this->fieldlist_contas->addDetail($this->default_item_fieldlist_contas);

        $this->fieldlist_contas->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //<onFormClear>

        //</onFormClear>

    }

    public function onShow($param = null)
    {
        $this->fieldlist_contas->addHeader();
        $this->fieldlist_contas->addDetail($this->default_item_fieldlist_contas);

        $this->fieldlist_contas->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

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
