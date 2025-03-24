<?php

class PessoaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'meusalao';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_PessoaForm';

    //<classProperties>

    //</classProperties>

    use BuilderMasterDetailFieldListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de pessoa");

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $id = new THidden('id');
        $post_class = new THidden('post_class');
        $post_method = new THidden('post_method');
        $nome = new TEntry('nome');
        $fone = new TEntry('fone');
        $email = new TEntry('email');
        $documento = new TEntry('documento');
        $obs = new TText('obs');
        $pessoa_contato_pessoa_id = new THidden('pessoa_contato_pessoa_id[]');
        $pessoa_contato_pessoa___row__id = new THidden('pessoa_contato_pessoa___row__id[]');
        $pessoa_contato_pessoa___row__data = new THidden('pessoa_contato_pessoa___row__data[]');
        $pessoa_contato_pessoa_nome = new TEntry('pessoa_contato_pessoa_nome[]');
        $pessoa_contato_pessoa_email = new TEntry('pessoa_contato_pessoa_email[]');
        $pessoa_contato_pessoa_telefone = new TEntry('pessoa_contato_pessoa_telefone[]');
        $pessoa_contato_pessoa_obs = new TEntry('pessoa_contato_pessoa_obs[]');
        $this->detalhe_de_contatos = new TFieldList();

        $this->detalhe_de_contatos->addField(null, $pessoa_contato_pessoa_id, []);
        $this->detalhe_de_contatos->addField(null, $pessoa_contato_pessoa___row__id, ['uniqid' => true]);
        $this->detalhe_de_contatos->addField(null, $pessoa_contato_pessoa___row__data, []);
        $this->detalhe_de_contatos->addField(new TLabel("Nome", null, '14px', null), $pessoa_contato_pessoa_nome, ['width' => '25%']);
        $this->detalhe_de_contatos->addField(new TLabel("Email", null, '14px', null), $pessoa_contato_pessoa_email, ['width' => '25%']);
        $this->detalhe_de_contatos->addField(new TLabel("Telefone", null, '14px', null), $pessoa_contato_pessoa_telefone, ['width' => '25%']);
        $this->detalhe_de_contatos->addField(new TLabel("Obs.", null, '14px', null), $pessoa_contato_pessoa_obs, ['width' => '25%']);

        $this->detalhe_de_contatos->width = '100%';
        $this->detalhe_de_contatos->setFieldPrefix('pessoa_contato_pessoa');
        $this->detalhe_de_contatos->name = 'detalhe_de_contatos';

        $this->criteria_detalhe_de_contatos = new TCriteria();
        $this->default_item_detalhe_de_contatos = new stdClass();

        $this->form->addField($pessoa_contato_pessoa_id);
        $this->form->addField($pessoa_contato_pessoa___row__id);
        $this->form->addField($pessoa_contato_pessoa___row__data);
        $this->form->addField($pessoa_contato_pessoa_nome);
        $this->form->addField($pessoa_contato_pessoa_email);
        $this->form->addField($pessoa_contato_pessoa_telefone);
        $this->form->addField($pessoa_contato_pessoa_obs);

        $this->detalhe_de_contatos->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $nome->addValidation("Nome", new TRequiredValidator()); 
        $email->addValidation("Email", new TEmailValidator(), []); 
        // $documento->addValidation("CPF", new TCPFValidator(), []); 
        $pessoa_contato_pessoa_email->addValidation("Email", new TEmailValidator(), []); 

        $id->setValue($param["key"] ?? "");
        $post_method->setValue($param["post_method"] ?? "onShow");
        $post_class->setValue($param["post_class"] ?? "PessoaList");

        $fone->setMask('(99) 9 9999-9999');
        $documento->setMask('999.999.999-99');
        $pessoa_contato_pessoa_telefone->setMask('(99) 9 9999-9999');

        $nome->setMaxLength(500);
        $fone->setMaxLength(255);
        $email->setMaxLength(255);
        $documento->setMaxLength(20);

        $id->setSize(200);
        $nome->setSize('100%');
        $fone->setSize('100%');
        $email->setSize('100%');
        $post_class->setSize(200);
        $obs->setSize('100%', 70);
        $post_method->setSize(200);
        $documento->setSize('100%');
        $pessoa_contato_pessoa_obs->setSize('100%');
        $pessoa_contato_pessoa_nome->setSize('100%');
        $pessoa_contato_pessoa_email->setSize('100%');
        $pessoa_contato_pessoa_telefone->setSize('100%');

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>

        $this->form->appendPage("Dados gerais");

        $this->form->addFields([new THidden('current_tab')]);
        $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");

        $row1 = $this->form->addFields([$id,$post_class],[$post_method]);
        $row1->layout = ['col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel(new TImage('fas:audio-description #333333')."Nome <span class='mand-field'>*</span>", null, '14px', null, '100%'),$nome]);
        $row2->layout = [' col-sm-8'];

        $row3 = $this->form->addFields([new TLabel(new TImage('fas:phone-alt #333333')."Telefone <span class='mand-field'>*</span>", null, '14px', null, '100%'),$fone],[new TLabel(new TImage('fas:envelope #333333')."Email ", null, '14px', null, '100%'),$email]);
        $row3->layout = ['col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([new TLabel(new TImage('fas:address-card #333333')."CPF", null, '14px', null, '100%'),$documento]);
        $row4->layout = ['col-sm-6'];

        $row5 = $this->form->addFields([new TLabel(new TImage('fas:align-justify #333333')."Observação:", null, '14px', null, '100%'),$obs]);
        $row5->layout = [' col-sm-12'];

        $row6 = $this->form->addContent([new TFormSeparator("Outros contatos", '#333', '18', '#eee')]);
        $row7 = $this->form->addFields([$this->detalhe_de_contatos]);
        $row7->layout = [' col-sm-12'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
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

        $style = new TStyle('right-panel > .container-part[page-name=PessoaForm]');
        $style->show(true);

    }

//<generated-FormAction-onSave>
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database);
            $messageAction = null;

            $this->form->validate();

            $object = new Pessoa(); // create an empty object //</blockLine>

            $data = $this->form->getData(); 
            $object->fromArray( (array) $data); 

            //</beforeStoreAutoCode> //</blockLine>

            $object->store(); // save the object //</blockLine>

            //</afterStoreAutoCode> //</blockLine>

    //<fieldList-2487457-20223481> //</hideLine>
            $pessoa_contato_pessoa_items = $this->storeItems('PessoaContato', 'pessoa_id', $object, $this->detalhe_de_contatos, function($masterObject, $detailObject){ //</blockLine>

                //</autoCode>
            }, $this->criteria_detalhe_de_contatos); //</blockLine>
    //</hideLine> //</fieldList-2487457-20223481>

            $data->id = $object->id; //</blockLine>

            $this->form->setData($data);

            TTransaction::close();

            //</messageAutoCode> //</blockLine>
//<generatedAutoCode>
            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
//</generatedAutoCode>

            TApplication::loadPage($param['post_class'], $param['post_method']);

            //</endTryAutoCode> //</blockLine>
//<generatedAutoCode>
            TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

//</generatedAutoCode>

        }
        catch (Exception $e)
        {
            //</catchAutoCode> //</blockLine> 

            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
//</generated-FormAction-onSave>

//<generated-onEdit>
    public function onEdit( $param )//</ini>
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Pessoa($key); // instantiates the Active Record //</blockLine>

                //</beforeSetDataAutoCode> //</blockLine>

    //<fieldList-2487457-20223481> //</hideLine>
                $this->detalhe_de_contatos_items = $this->loadItems('PessoaContato', 'pessoa_id', $object, $this->detalhe_de_contatos, function($masterObject, $detailObject, $objectItems){ //</blockLine>

                    //code here

                    //</autoCode>
                }, $this->criteria_detalhe_de_contatos); //</blockLine>
    //</hideLine> //</fieldList-2487457-20223481>

                $this->form->setData($object); // fill the form //</blockLine>

                //</afterSetDataAutoCode> //</blockLine>
//<generatedAutoCode>

//</generatedAutoCode>

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }//</end>
//</generated-onEdit>

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->detalhe_de_contatos->addHeader();
        $this->detalhe_de_contatos->addDetail($this->default_item_detalhe_de_contatos);

        $this->detalhe_de_contatos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //<onFormClear>

        //</onFormClear>

    }

    public function onShow($param = null)
    {
        $this->detalhe_de_contatos->addHeader();
        $this->detalhe_de_contatos->addDetail($this->default_item_detalhe_de_contatos);

        $this->detalhe_de_contatos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

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
