<?php

//<fileHeader>

//</fileHeader>

class ProcedimentoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'meusalao';
    private static $activeRecord = 'Procedimento';
    private static $primaryKey = 'id';
    private static $formName = 'form_ProcedimentoList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
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

        if (!empty($param['target_container'])) {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Listagem de procedimentos");
        $this->limit = 20;

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $ativo = new TRadioGroup('ativo');


        $ativo->setSize('100%');
        $ativo->addItems(["T" => "Sim", "F" => "Não", "" => "Todos"]);
        $ativo->setLayout('horizontal');
        $ativo->setUseButton();
        $ativo->setBreakItems(3);

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([new TLabel("Ativo :", null, '14px', null, '100%'), $ativo], []);
        $row1->layout = ['col-sm-6', 'col-sm-6'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        $startHidden = true;

        if (TSession::getValue('ProcedimentoList_expand_start_hidden') === false) {
            $startHidden = false;
        } elseif (TSession::getValue('ProcedimentoList_expand_start_hidden') === true) {
            $startHidden = true;
        }
        $expandButton = $this->form->addExpandButton("Filtros", 'fas:filter #000000', $startHidden);
        $expandButton->addStyleClass('btn-default');
        $expandButton->setAction(new TAction([$this, 'onExpandForm'], ['static' => 1]), "Filtros");
        $this->form->addField($expandButton);

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary');

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['ProcedimentoForm', 'onShow']), 'fas:plus #69aa46');
        $this->btn_onshow = $btn_onshow;

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();
        $this->datagrid->setId(__CLASS__ . '_datagrid');

        $this->datagrid_form = new TForm('datagrid_' . self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_id = new TDataGridColumn('id', "Id", 'center', '70px');
        $column_nome = new TDataGridColumn('nome', "Nome", 'left');
        $column_tempo_estimado = new TDataGridColumn('tempo_estimado', "Tempo estimado (em horas)", 'left');
        $column_ativo_transformed = new TDataGridColumn('ativo', "Ativo ", 'left');

        $column_ativo_transformed->setTransformer(function ($value, $object, $row, $cell = null, $last_row = null) {
            if ($value === true || $value == 't' || $value === 1 || $value == '1' || $value == 's' || $value == 'S' || $value == 'T') {
                return 'Sim';
            } elseif ($value === false || $value == 'f' || $value === 0 || $value == '0' || $value == 'n' || $value == 'N' || $value == 'F') {
                return 'Não';
            }

            return $value;
        });

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        //<onBeforeColumnsCreation>

        //</onBeforeColumnsCreation>

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_tempo_estimado);
        $this->datagrid->addColumn($column_ativo_transformed);

        //<onAfterColumnsCreation>

        //</onAfterColumnsCreation>

        $action_onEdit = new TDataGridAction(array('ProcedimentoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('ProcedimentoList', 'onDelete'));
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

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup();
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $button_cadastrar = new TButton('button_button_cadastrar');
        $button_cadastrar->setAction(new TAction(['ProcedimentoForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69AA46');

        $this->datagrid_form->addField($button_cadastrar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction("XLS", new TAction(['ProcedimentoList', 'onExportXls'], ['static' => 1]), 'datagrid_' . self::$formName, 'fas:file-excel #4CAF50');
        $dropdown_button_exportar->addPostAction("PDF", new TAction(['ProcedimentoList', 'onExportPdf'], ['static' => 1]), 'datagrid_' . self::$formName, 'far:file-pdf #e74c3c');

        $head_left_actions->add($button_cadastrar);

        $head_right_actions->add($dropdown_button_exportar);

        //<onAfterHeaderActionsCreation>

        //</onAfterHeaderActionsCreation>

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if (empty($param['target_container'])) {
            // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        }
        $container->add($this->form);

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
                $object = new Procedimento($key, FALSE); //</blockLine>

                // deletes the object from the database
                $object->delete();

                // close the transaction
                TTransaction::close();

                // reload the listing
                $this->onReload($param);
                // shows the success message
                new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'));
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
    //<generated-DatagridHeaderAction-onExportXls>
    public function onExportXls($param = null)
    {
        try {
            $output = 'app/output/' . uniqid() . '.xls';

            if ((!file_exists($output) && is_writable(dirname($output))) or is_writable($output)) {
                $widths = [];
                $titles = [];

                foreach ($this->datagrid->getColumns() as $column) {
                    $titles[] = $column->getLabel();
                    $width    = 100;

                    if (is_null($column->getWidth())) {
                        $width = 100;
                    } else if (strpos((string)$column->getWidth(), '%') !== false) {
                        $width = ((int) $column->getWidth()) * 5;
                    } else if (is_numeric($column->getWidth())) {
                        $width = $column->getWidth();
                    }

                    $widths[] = $width;
                }

                $table = new \TTableWriterXLS($widths);
                $table->addStyle('title',  'Helvetica', '10', 'B', '#ffffff', '#617FC3');
                $table->addStyle('data',   'Helvetica', '10', '',  '#000000', '#FFFFFF', 'LR');

                $table->addRow();

                foreach ($titles as $title) {
                    $table->addCell($title, 'center', 'title');
                }

                $this->limit = 0;
                $objects = $this->onReload();

                TTransaction::open(self::$database);
                if ($objects) {
                    foreach ($objects as $object) {
                        $table->addRow();
                        foreach ($this->datagrid->getColumns() as $column) {
                            $column_name = $column->getName();
                            $value = '';
                            if (isset($object->$column_name)) {
                                $value = is_scalar($object->$column_name) ? $object->$column_name : '';
                            } else if (method_exists($object, 'render')) {
                                $column_name = (strpos((string)$column_name, '{') === FALSE) ? ('{' . $column_name . '}') : $column_name;
                                $value = $object->render($column_name);
                            }

                            $transformer = $column->getTransformer();
                            if ($transformer) {
                                $value = strip_tags((string)call_user_func($transformer, $value, $object, null));
                            }

                            $table->addCell($value, 'center', 'data');
                        }
                    }
                }
                $table->save($output);
                TTransaction::close();

                TPage::openFile($output);
            } else {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    //</generated-DatagridHeaderAction-onExportXls>
    //<generated-DatagridHeaderAction-onExportPdf>
    public function onExportPdf($param = null)
    {
        try {
            $output = 'app/output/' . uniqid() . '.pdf';

            if ((!file_exists($output) && is_writable(dirname($output))) or is_writable($output)) {
                $this->limit = 0;
                $this->datagrid->prepareForPrinting();
                $this->onReload();

                $html = clone $this->datagrid;
                $contents = file_get_contents('app/resources/styles-print.html') . $html->getContents();

                $dompdf = new \Dompdf\Dompdf;
                $dompdf->loadHtml($contents);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                file_put_contents($output, $dompdf->output());

                $window = TWindow::create('PDF', 0.8, 0.8);
                $object = new TElement('iframe');
                $object->src  = $output;
                $object->type  = 'application/pdf';
                $object->style = "width: 100%; height:calc(100% - 10px)";

                $window->add($object);
                $window->show();
            } else {
                throw new Exception(_t('Permission denied') . ': ' . $output);
            }
        } catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    //</generated-DatagridHeaderAction-onExportPdf>

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        //<onBeforeDatagridSearch>

        //</onBeforeDatagridSearch> 

        TSession::setValue(__CLASS__ . '_filter_data', NULL);
        TSession::setValue(__CLASS__ . '_filters', NULL);

        if (isset($data->ativo) and ((is_scalar($data->ativo) and $data->ativo !== '') or (is_array($data->ativo) and (!empty($data->ativo))))) {

            $filters[] = new TFilter('ativo', '=', $data->ativo); // create the filter 
        }

        //<onDatagridSearch>

        //</onDatagridSearch>

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        TSession::setValue(__CLASS__ . '_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try {
            // open a transaction with database 'meusalao'
            TTransaction::open(self::$database);

            // creates a repository for Procedimento
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

    public static function onExpandForm($param = null)
    {
        try {
            $startHidden = true;

            if (TSession::getValue('ProcedimentoList_expand_start_hidden') === false) {
                TSession::setValue('ProcedimentoList_expand_start_hidden', true);
            } elseif (TSession::getValue('ProcedimentoList_expand_start_hidden') === true) {
                TSession::setValue('ProcedimentoList_expand_start_hidden', false);
            } else {
                TSession::setValue('ProcedimentoList_expand_start_hidden', !$startHidden);
            }

            //<onExpandFormEndFunction>

            //</onExpandFormEndFunction>

        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
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

        $object = new Procedimento($id);

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
