<?php

//<fileHeader>

//</fileHeader>

class ContaReceberForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'meusalao';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContaReceberForm';

    //<classProperties>

    //</classProperties>

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
        $this->form->setFormTitle("Cadastro de conta a receber");

        $criteria_pessoa_id = new TCriteria();
        $criteria_categoria_id = new TCriteria();
        $criteria_forma_pagamento_id = new TCriteria();

        /*$filterVar = GrupoPessoa::CLIENTE;
        $criteria_pessoa_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_pessoa_id = '{$filterVar}')"));*/
        $filterVar = TipoConta::RECEBER;
        $criteria_categoria_id->add(new TFilter('tipo_conta_id', '=', $filterVar));

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $id = new THidden('id');
        $pessoa_id = new TDBCombo('pessoa_id', 'meusalao', 'Pessoa', 'id', '{nome}', 'nome asc', $criteria_pessoa_id);
        $categoria_id = new TDBCombo('categoria_id', 'meusalao', 'Categoria', 'id', '{nome}', 'nome asc', $criteria_categoria_id);
        $forma_pagamento_id = new TDBCombo('forma_pagamento_id', 'meusalao', 'FormaPagamento', 'id', '{nome}', 'nome asc', $criteria_forma_pagamento_id);
        $dt_emissao = new TDate('dt_emissao');
        $dt_pagamento = new TDate('dt_pagamento');
        $dt_vencimento = new TDate('dt_vencimento');
        $valor = new TNumeric('valor', '2', ',', '.');
        $parcela = new TSpinner('parcela');
        $obs = new TText('obs');

        $pessoa_id->addValidation("Pessoa", new TRequiredValidator());
        $categoria_id->addValidation("Categoria", new TRequiredValidator());
        $forma_pagamento_id->addValidation("Forma de pagamento", new TRequiredValidator());
        $valor->addValidation("Valor", new TRequiredValidator());

        $id->setValue($param["key"] ?? "");
        $dt_emissao->setValue(date('d/m/Y'));
        $valor->setAllowNegative(false);
        $parcela->setRange(1, 2000, 1);
        $pessoa_id->enableSearch();
        $categoria_id->enableSearch();
        $forma_pagamento_id->enableSearch();

        $dt_emissao->setMask('dd/mm/yyyy');
        $dt_pagamento->setMask('dd/mm/yyyy');
        $dt_vencimento->setMask('dd/mm/yyyy');

        $dt_emissao->setDatabaseMask('yyyy-mm-dd');
        $dt_pagamento->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(200);
        $valor->setSize('100%');
        $dt_emissao->setSize(130);
        $parcela->setSize('100%');
        $obs->setSize('100%', 80);
        $pessoa_id->setSize('100%');
        $dt_pagamento->setSize(130);
        $dt_vencimento->setSize(130);
        $categoria_id->setSize('100%');
        $forma_pagamento_id->setSize('100%');

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([$id], []);
        $row1->layout = ['col-sm-6', 'col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Cliente <span class='mand-field'>*</span>", null, '14px', null, '100%'), $pessoa_id], [new TLabel("Categoria <span class='mand-field'>*</span>", null, '14px', null, '100%'), $categoria_id]);
        $row2->layout = ['col-sm-6', 'col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Forma de pagamento <span class='mand-field'>*</span>", null, '14px', null, '100%'), $forma_pagamento_id], []);
        $row3->layout = ['col-sm-6', 'col-sm-6'];

        $row4 = $this->form->addFields([new TLabel("Data de emissão:", null, '14px', null, '100%'), $dt_emissao], [new TLabel("Data de pagamento:", null, '14px', null, '100%'), $dt_pagamento], [new TLabel("Data de vencimento:", null, '14px', null, '100%'), $dt_vencimento]);
        $row4->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        $row5 = $this->form->addFields([new TLabel("Valor <span class='mand-field'>*</span>", null, '14px', null, '100%'), $valor], [new TLabel("Parcela:", null, '14px', null, '100%'), $parcela]);
        $row5->layout = [' col-sm-3', ' col-sm-3'];

        $row6 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'), $obs]);
        $row6->layout = [' col-sm-12'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
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

    //<generated-FormAction-onSave>
    public function onSave($param = null)
    {
        try {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Conta(); // create an empty object //</blockLine>

            $data = $this->form->getData(); // get form data as array
            $object->fromArray((array) $data); // load the object with data

            //</beforeStoreAutoCode> //</blockLine>

            $object->tipo_conta_id = TipoConta::RECEBER;

            $object->store(); // save the object //</blockLine>

            //</afterStoreAutoCode> //</blockLine>
            //<generatedAutoCode>

            $loadPageParam = [];

            if (!empty($param['target_container'])) {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            //</generatedAutoCode>

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; //</blockLine>

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            //</messageAutoCode> //</blockLine>
            //<generatedAutoCode>
            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ContaReceberList', 'onShow', $loadPageParam);
            //</generatedAutoCode>

            //</endTryAutoCode> //</blockLine>
            //<generatedAutoCode>
            TScript::create("Template.closeRightPanel();");
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

                $object = new Conta($key); // instantiates the Active Record //</blockLine>

                //</beforeSetDataAutoCode> //</blockLine>

                $this->form->setData($object); // fill the form //</blockLine>

                //</afterSetDataAutoCode> //</blockLine>
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

        //<onFormClear>

        //</onFormClear>

    }

    public function onShow($param = null)
    {

        //<onShow>

        //</onShow>
    }

    public static function getFormName()
    {
        return self::$formName;
    }

    //</hideLine> <addUserFunctionsCode/>

    //<generated-userFunction-onQuitar>
    public  function onQuitar($param = null)
    {
        try {

            $this->onEdit($param);

            $this->form->getField('pessoa_id')->setEditable(false);
            $this->form->getField('categoria_id')->setEditable(false);
            $this->form->getField('valor')->setEditable(false);
            $this->form->getField('dt_vencimento')->setEditable(false);
            $this->form->getField('dt_emissao')->setEditable(false);
            $this->form->getField('parcela')->setEditable(false);

            $this->form->getField('dt_pagamento')->setValue(date('Y-m-d'));

            $this->form->setFormTitle("Quitar conta a receber");
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    //</generated-userFunction-onQuitar>

    //<userCustomFunctions>

    //</userCustomFunctions>

}
