<?php

//<fileHeader>

//</fileHeader>

use Adianti\Widget\Util\TImage;

class PessoaFormView extends TPage
{
    protected $form; // form
    private static $database = 'meusalao';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'formView_Pessoa';

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

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        //<onBeforeLoadObject>

        //</onBeforeLoadObject>

        $pessoa = new Pessoa($param['key']);
        // define the form title
        $this->form->setFormTitle("Consulta de Pessoa");

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $label2 = new TLabel(new TImage('fas:hashtag #333') . " Id:", '', '14px', 'B', '100%');
        $text1 = new TTextDisplay($pessoa->id, '', '16px', '');
        $label4 = new TLabel(new TImage('fas:calendar') . " Criado em:", '', '14px', 'B', '100%');
        $text10 = new TTextDisplay(TDateTime::convertToMask($pessoa->created_at, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '', '16px', '');
        $label6 = new TLabel(new TImage('fas:calendar') . " Atualizado em:", '', '14px', 'B', '100%');
        $text11 = new TTextDisplay(TDateTime::convertToMask($pessoa->updated_at, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '', '16px', '');
        $label8 = new TLabel(new TImage('fas:audio-description #333') . " Nome:", '', '14px', 'B', '100%');
        $text5 = new TTextDisplay($pessoa->nome, '', '16px', '');
        $label14 = new TLabel(new TImage('fas:address-card #333') . "CPF:", '', '14px', 'B', '100%');
        $text6 = new TTextDisplay($pessoa->documento, '', '16px', '');
        $label16 = new TLabel(new TImage('fas:envelope #333') . " Email:", '', '14px', 'B', '100%');
        $text9 = new TTextDisplay($pessoa->email, '', '16px', '');
        $label18 = new TLabel(new TImage('fas:phone-alt #333') . " Telefone:", '', '14px', 'B', '100%');
        $text8 = new TTextDisplay($pessoa->fone, '', '16px', '');
        $label20 = new TLabel(new TImage('fas:align-justify #333') . "Observação:", '', '14px', 'B', '100%');
        $text7 = new TTextDisplay($pessoa->obs, '', '16px', '');
        
        $action2 = new TActionLink("Gerar Extrato", new TAction(['PessoaContaPagarEmAbertoDocument', 'onGenerate'], ['key' => $pessoa->id]), '', '12px', '', 'fas:file-invoice-dollar #F44336');
        $bpagecontainer2 = new BPageContainer();
        $action_gerar_extrato = new TActionLink("Gerar Extrato", new TAction(['PessoaContaReceberEmAbertoDocument', 'onGenerate'], ['key' => $pessoa->id]), '', '12px', '', 'fas:file-invoice-dollar #4CAF50');
        $bpagecontainer_contas_em_aberto = new BPageContainer();

        $text6->enableToggleVisibility(true);
        $bpagecontainer2->setSize('100%');
        $bpagecontainer_contas_em_aberto->setSize('100%');

        $bpagecontainer2->setAction(new TAction(['ContaPagarEmAbertoSimpleList', 'onShow'], ['pessoa_id' => $pessoa->id]));
        $bpagecontainer_contas_em_aberto->setAction(new TAction(['ContaReceberEmAbertoSimpleList', 'onShow'], ['pessoa_id' => $pessoa->id]));

        $bpagecontainer2->setId('b6308052456360');
        $bpagecontainer_contas_em_aberto->setId('b6317a595893e4');

        $action2->class = 'btn btn-default';
        $action_gerar_extrato->class = 'btn btn-default';

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $bpagecontainer2->add($loadingContainer);
        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $bpagecontainer_contas_em_aberto->add($loadingContainer);

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([$label2, $text1], [$label4, $text10], [$label6, $text11]);
        $row1->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        // $row2 = $this->form->addContent([new TFormSeparator("", '#333', '18', '#eee')]);
        $row3 = $this->form->addFields([$label8, $text5]);
        $row3->layout = [' col-sm-4'];

        $row4 = $this->form->addFields([$label14, $text6], [$label16, $text9], [$label18, $text8]);
        $row4->layout = [' col-sm-4', ' col-sm-4', ' col-sm-4'];

        $row5 = $this->form->addFields([$label20, $text7]);
        $row5->layout = [' col-sm-8'];

        $tab_622940daf9f3b = new BootstrapFormBuilder('tab_622940daf9f3b');
        $this->tab_622940daf9f3b = $tab_622940daf9f3b;
        $tab_622940daf9f3b->setProperty('style', 'border:none; box-shadow:none;');

        $tab_622940daf9f3b->appendPage("Financeiro");

        $tab_6308050d6056b = new BootstrapFormBuilder('tab_6308050d6056b');
        $this->tab_6308050d6056b = $tab_6308050d6056b;
        $tab_6308050d6056b->setProperty('style', 'border:none; box-shadow:none;');

        $tab_6308050d6056b->appendPage("Contas a pagar em aberto");

        $tab_6308050d6056b->addFields([new THidden('current_tab_tab_6308050d6056b')]);
        $tab_6308050d6056b->setTabFunction("$('[name=current_tab_tab_6308050d6056b]').val($(this).attr('data-current_page'));");

        $row6 = $tab_6308050d6056b->addFields([$action2], [], []);
        $row6->layout = ['col-sm-3', 'col-sm-3', 'col-sm-6'];

        $row7 = $tab_6308050d6056b->addFields([$bpagecontainer2]);
        $row7->layout = [' col-sm-12'];

        $tab_6308050d6056b->appendPage("Contas a receber em aberto");
        $row8 = $tab_6308050d6056b->addFields([$action_gerar_extrato], [], []);
        $row8->layout = ['col-sm-3', 'col-sm-3', 'col-sm-6'];

        $row9 = $tab_6308050d6056b->addFields([$bpagecontainer_contas_em_aberto]);
        $row9->layout = [' col-sm-12'];

        $row10 = $tab_622940daf9f3b->addFields([$tab_6308050d6056b]);
        $row10->layout = [' col-sm-12'];

        $row11 = $this->form->addFields([$tab_622940daf9f3b]);
        $row11->layout = [' col-sm-12'];

        if (!empty($param['current_tab'])) {
            $this->form->setCurrentPage($param['current_tab']);
        }

        if (!empty($param['current_tab_tab_622940daf9f3b'])) {
            $this->tab_622940daf9f3b->setCurrentPage($param['current_tab_tab_622940daf9f3b']);
        }
        if (!empty($param['current_tab_tab_6308050d6056b'])) {
            $this->tab_6308050d6056b->setCurrentPage($param['current_tab_tab_6308050d6056b']);
        }

        $tab_622940daf9f3b->appendPage("Contatos");

        $tab_622940daf9f3b->addFields([new THidden('current_tab_tab_622940daf9f3b')]);
        $tab_622940daf9f3b->setTabFunction("$('[name=current_tab_tab_622940daf9f3b]').val($(this).attr('data-current_page'));");

        $this->pessoa_contato_pessoa_id_list = new TQuickGrid;
        $this->pessoa_contato_pessoa_id_list->disableHtmlConversion();
        $this->pessoa_contato_pessoa_id_list->style = 'width:100%';
        $this->pessoa_contato_pessoa_id_list->disableDefaultClick();

        $column_nome = $this->pessoa_contato_pessoa_id_list->addQuickColumn("Nome", 'nome', 'left');
        $column_email = $this->pessoa_contato_pessoa_id_list->addQuickColumn("Email", 'email', 'left');
        $column_telefone = $this->pessoa_contato_pessoa_id_list->addQuickColumn("Telefone", 'telefone', 'left');
        $column_obs = $this->pessoa_contato_pessoa_id_list->addQuickColumn("Obs", 'obs', 'left');

        $this->pessoa_contato_pessoa_id_list->createModel();

        $criteria_pessoa_contato_pessoa_id = new TCriteria();
        $criteria_pessoa_contato_pessoa_id->add(new TFilter('pessoa_id', '=', $pessoa->id));

        $criteria_pessoa_contato_pessoa_id->setProperty('order', 'id desc');

        $pessoa_contato_pessoa_id_items = PessoaContato::getObjects($criteria_pessoa_contato_pessoa_id);

        $this->pessoa_contato_pessoa_id_list->addItems($pessoa_contato_pessoa_id_items);

        $panel = new TElement('div');
        $panel->class = 'formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->pessoa_contato_pessoa_id_list));

        $tab_622940daf9f3b->addContent([$panel]);

        $btnPessoaFormOnEditAction = new TAction(['PessoaForm', 'onEdit'], ['key' => $pessoa->id]);
        $btnPessoaFormOnEditLabel = new TLabel("Editar");

        $btnPessoaFormOnEdit = $this->form->addHeaderAction($btnPessoaFormOnEditLabel, $btnPessoaFormOnEditAction, 'fas:edit #2196F3');
        $btnPessoaFormOnEditLabel->setFontSize('12px');
        $btnPessoaFormOnEditLabel->setFontColor('#333');

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

        TTransaction::close();
        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=PessoaFormView]');
        $style->width = '100% !important';
        $style->show(true);
    }

    public function onShow($param = null)
    {
        //<onShow>

        //</onShow>
    }

    //</hideLine> <addUserFunctionsCode/>

    //<userCustomFunctions>

    //</userCustomFunctions>
}
