<?php

//<fileHeader>

//</fileHeader>

class ContaAnexoHeaderList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'minierp';
    private static $activeRecord = 'ContaAnexo';
    private static $primaryKey = 'id';
    private static $formName = 'formList_ContaAnexo';
    private $showMethods = ['onReload', 'onSearch'];
    private $limit = 20;

    //<classProperties>

    //</classProperties>

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();
        // creates the form

        if (!empty($param['target_container'])) {
            $this->adianti_target_container = $param['target_container'];
        }

        $this->limit = 20;

        $criteria_tipo_anexo_id = new TCriteria();

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $tipo_anexo_id = new TDBCombo('tipo_anexo_id', 'minierp', 'TipoAnexo', 'id', '{nome}', 'nome asc', $criteria_tipo_anexo_id);
        $descricao = new TEntry('descricao');

        $descricao->exitOnEnter();

        $descricao->setExitAction(new TAction([$this, 'onSearch'], ['static' => '1', 'target_container' => $param['target_container'] ?? null]));

        $tipo_anexo_id->setChangeAction(new TAction([$this, 'onSearch'], ['static' => '1', 'target_container' => $param['target_container'] ?? null]));

        $tipo_anexo_id->enableSearch();
        $descricao->setSize('100%');
        $tipo_anexo_id->setSize('100%');

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid->setId(__CLASS__ . '_datagrid');

        $this->datagrid_form = new TForm(self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        if (!empty($param['conta_id'])) {
            TSession::setValue(__CLASS__ . 'load_filter_conta_id', $param['conta_id']);
        }
        $filterVar = TSession::getValue(__CLASS__ . 'load_filter_conta_id');
        $this->filter_criteria->add(new TFilter('conta_id', '=', $filterVar));

        $this->datagrid->disableDefaultClick();
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);

        $column_tipo_anexo_nome = new TDataGridColumn('tipo_anexo->nome', "Tipo anexo", 'left');
        $column_descricao = new TDataGridColumn('descricao', "Descrição", 'left');
        $column_arquivo_transformed = new TDataGridColumn('arquivo', "Arquivo", 'left');

        $column_arquivo_transformed->setTransformer(function ($value, $object, $row, $cell = null, $last_row = null) {
            $value = explode(',', $value);
            if (count($value) == 0) {
                $value = $value[0];
            }

            if (is_array($value)) {
                $files = $value;
                $divFiles = new TElement('div');
                foreach ($files as $file) {
                    $fileName = $file;
                    if (strpos($file, '%7B') !== false) {
                        if (!empty($file)) {
                            $fileObject = json_decode(urldecode($file));

                            $fileName = $fileObject->fileName;
                        }
                    }

                    $a = new TElement('a');
                    $a->href = "download.php?file={$fileName}";
                    $a->class = 'btn btn-link';
                    $a->add($fileName);
                    $a->target = '_blank';

                    $divFiles->add($a);
                }

                return $divFiles;
            } else {
                if (strpos($value, '%7B') !== false) {
                    if (!empty($value)) {
                        $value_object = json_decode(urldecode($value));
                        $value = $value_object->fileName;
                    }
                }

                if ($value) {
                    $a = new TElement('a');
                    $a->href = "download.php?file={$value}";
                    $a->class = 'btn btn-default';
                    $a->add($value);
                    $a->target = '_blank';

                    return $a;
                }

                return $value;
            }
        });

        //<onBeforeColumnsCreation>

        //</onBeforeColumnsCreation>

        $this->datagrid->addColumn($column_tipo_anexo_nome);
        $this->datagrid->addColumn($column_descricao);
        $this->datagrid->addColumn($column_arquivo_transformed);

        //<onAfterColumnsCreation>

        //</onAfterColumnsCreation>

        $action_onEdit = new TDataGridAction(array('ContaAnexoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ContaAnexoHeaderList', 'onDelete'));
        $action_onDelete->setUseButton(false);
        $action_onDelete->setButtonClass('btn btn-default btn-sm');
        $action_onDelete->setLabel("Excluir");
        $action_onDelete->setImage('fas:trash-alt #dd5a43');
        $action_onDelete->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onDelete);

        //<onAfterActionsCreation>

        //</onAfterActionsCreation>

        // create the datagrid model
        $this->datagrid->createModel();

        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr);

        if (!$action_onEdit->isHidden()) {
            $tr->add(TElement::tag('td', ''));
        }
        if (!$action_onDelete->isHidden()) {
            $tr->add(TElement::tag('td', ''));
        }
        $td_tipo_anexo_id = TElement::tag('td', $tipo_anexo_id);
        $tr->add($td_tipo_anexo_id);
        $td_descricao = TElement::tag('td', $descricao);
        $tr->add($td_descricao);
        $td_empty = TElement::tag('td', "");
        $tr->add($td_empty);

        $this->datagrid_form->addField($tipo_anexo_id);
        $this->datagrid_form->addField($descricao);

        $this->datagrid_form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $this->datagrid->disableDefaultClick();

        $panel = new TPanelGroup("Listagem de anexos");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $this->datagrid_form->add($this->datagrid);
        $panel->add($headerActions);
        $panel->add($this->datagrid_form);

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['ContaAnexoForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69aa46');

        $this->datagrid_form->addField($button_cadastrar);

        $head_left_actions->add($button_cadastrar);

        //<onAfterHeaderActionsCreation>

        //</onAfterHeaderActionsCreation>

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if (empty($param['target_container'])) {
            $container->add(TBreadCrumb::create(["Financeiro", "Anexos"]));
        }

        $container->add($panel);
        //<onAfterPageCreation>

        //</onAfterPageCreation>

        parent::add($container);
    }

    //<generated-DatagridAction-onDelete>
    public function onDelete($param = null)
    {
        if (isset($param['delete']) && $param['delete'] == 1) {
            try {
                // get the paramseter $key
                $key = $param['key'];
                // open a transaction with database
                TTransaction::open(self::$database);

                // instantiates object
                $object = new ContaAnexo($key, FALSE); //</blockLine>

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload($param);
                // shows the success message
                TToast::show('success', AdiantiCoreTranslator::translate('Record deleted'), 'topRight', 'far:check-circle');
            } catch (Exception $e) // in case of exception
            {
                // shows the exception error message
                new TMessage('error', $e->getMessage());
                // undo all pending operations
                TTransaction::rollback();
            }
        } else {
            // define the delete action
            $action = new TAction(array($this, 'onDelete'));
            $action->setParameters($param); // pass the key paramseter ahead
            $action->setParameter('delete', 1);
            // shows a dialog to the user
            new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
        }
    }
    //</generated-DatagridAction-onDelete>

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        // get the search form data
        $data = $this->datagrid_form->getData();
        $filters = [];

        //<onBeforeDatagridSearch>

        //</onBeforeDatagridSearch> 

        TSession::setValue(__CLASS__ . '_filter_data', NULL);
        TSession::setValue(__CLASS__ . '_filters', NULL);

        if (isset($data->tipo_anexo_id) and ((is_scalar($data->tipo_anexo_id) and $data->tipo_anexo_id !== '') or (is_array($data->tipo_anexo_id) and (!empty($data->tipo_anexo_id))))) {

            $filters[] = new TFilter('tipo_anexo_id', '=', $data->tipo_anexo_id); // create the filter 
        }

        if (isset($data->descricao) and ((is_scalar($data->descricao) and $data->descricao !== '') or (is_array($data->descricao) and (!empty($data->descricao))))) {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%"); // create the filter 
        }

        //<onDatagridSearch>

        //</onDatagridSearch>

        // fill the form with data again
        $this->datagrid_form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        TSession::setValue(__CLASS__ . '_filters', $filters);

        if (isset($param['static']) && ($param['static'] == '1')) {
            $class = get_class($this);
            $onReloadParam = ['offset' => 0, 'first_page' => 1, 'target_container' => $param['target_container'] ?? null];
            AdiantiCoreApplication::loadPage($class, 'onReload', $onReloadParam);
            TScript::create('$(".select2").prev().select2("close");');
        } else {
            $this->onReload(['offset' => 0, 'first_page' => 1]);
        }
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try {
            // open a transaction with database 'minierp'
            TTransaction::open(self::$database);

            // creates a repository for ContaAnexo
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order'])) {
                $param['order'] = 'id';
            }
            if (empty($param['direction'])) {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if ($filters = TSession::getValue(__CLASS__ . '_filters')) {
                foreach ($filters as $filter) {
                    $criteria->add($filter);
                }
            }

            //<onBeforeDatagridLoad>

            //</onBeforeDatagridLoad>

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects) {
                // iterate the collection of active records
                foreach ($objects as $object) {
                    //<onBeforeDatagridAddItem>

                    //</onBeforeDatagridAddItem>
                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";
                    //<onAfterDatagridAddItem>

                    //</onAfterDatagridAddItem>
                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count = $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            //<onBeforeDatagridTransactionClose>

            //</onBeforeDatagridTransactionClose>

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        } catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {
        //<onShow>

        //</onShow>
    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded and (!isset($_GET['method']) or !(in_array($_GET['method'],  $this->showMethods)))) {
            if (func_num_args() > 0) {
                $this->onReload(func_get_arg(0));
            } else {
                $this->onReload();
            }
        }
        parent::show();
    }

    //</hideLine> <addUserFunctionsCode/>

    public static function manageRow($id, $param = [])
    {
        $list = new self($param);

        $openTransaction = TTransaction::getDatabase() != self::$database ? true : false;

        if ($openTransaction) {
            TTransaction::open(self::$database);
        }

        $object = new ContaAnexo($id);

        $row = $list->datagrid->addItem($object);
        $row->id = "row_{$object->id}";

        if ($openTransaction) {
            TTransaction::close();
        }

        TDataGrid::replaceRowById(__CLASS__ . '_datagrid', $row->id, $row);
    }

    //<userCustomFunctions>

    //</userCustomFunctions>

}
