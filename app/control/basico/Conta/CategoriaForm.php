<?php

//<fileHeader>

//</fileHeader>

class CategoriaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'meusalao';
    private static $activeRecord = 'Categoria';
    private static $primaryKey = 'id';
    private static $formName = 'form_CategoriaForm';

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
        $this->form->setFormTitle("Cadastro de categoria");

        $criteria_tipo_conta_id = new TCriteria();

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $id = new THidden('id');
        $nome = new TEntry('nome');
        $tipo_conta_id = new TDBCombo('tipo_conta_id', 'meusalao', 'TipoConta', 'id', '{nome}', 'nome asc', $criteria_tipo_conta_id);

        $nome->addValidation("Nome", new TRequiredValidator());
        $tipo_conta_id->addValidation("Tipo da conta", new TRequiredValidator());

        $id->setValue($param["key"] ?? "");
        $tipo_conta_id->enableSearch();
        $id->setSize(200);
        $nome->setSize('100%');
        $tipo_conta_id->setSize('100%');

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([$id]);
        $row1->layout = ['col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Nome <span class='mand-field'>*</span>", null, '14px', null, '100%'), $nome], [new TLabel("Tipo da conta <span class='mand-field'>*</span>", null, '14px', null, '100%'), $tipo_conta_id]);
        $row2->layout = ['col-sm-6', 'col-sm-6'];

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

            $object = new Categoria(); // create an empty object //</blockLine>

            $data = $this->form->getData(); // get form data as array
            $object->fromArray((array) $data); // load the object with data

            //</beforeStoreAutoCode> //</blockLine>

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
            TApplication::loadPage('CategoriaHeaderList', 'onShow', $loadPageParam);
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

                $object = new Categoria($key); // instantiates the Active Record //</blockLine>

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

    //<userCustomFunctions>

    //</userCustomFunctions>

}
