<?php

//<fileHeader>

//</fileHeader>

class AgendamentoList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'meusalao';
    private static $activeRecord = 'Agendamento';
    private static $primaryKey = 'id';
    private static $formName = 'form_AgendamentoList';
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
        $this->form->setFormTitle("Listagem de agendamentos");
        $this->limit = 20;

        $criteria_pessoa_id = new TCriteria();
        $criteria_estado_agendamento_id = new TCriteria();

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $pessoa_id = new TDBCombo('pessoa_id', 'meusalao', 'Pessoa', 'id', '{nome}', 'nome asc', $criteria_pessoa_id);
        $estado_agendamento_id = new TDBCombo('estado_agendamento_id', 'meusalao', 'EstadoAgendamento', 'id', '{nome}', 'nome asc', $criteria_estado_agendamento_id);
        $inicio = new TDate('inicio');
        $inicio_ate = new TDate('inicio_ate');
        $fim = new TDate('fim');
        $fim_ate = new TDate('fim_ate');


        $pessoa_id->enableSearch();
        $estado_agendamento_id->enableSearch();

        $fim->setMask('dd/mm/yyyy');
        $inicio->setMask('dd/mm/yyyy');
        $fim_ate->setMask('dd/mm/yyyy');
        $inicio_ate->setMask('dd/mm/yyyy');

        $fim->setDatabaseMask('yyyy-mm-dd');
        $inicio->setDatabaseMask('yyyy-mm-dd');
        $fim_ate->setDatabaseMask('yyyy-mm-dd');
        $inicio_ate->setDatabaseMask('yyyy-mm-dd');

        $fim->setSize(150);
        $inicio->setSize(150);
        $fim_ate->setSize(110);
        $inicio_ate->setSize(110);
        $pessoa_id->setSize('100%');
        $estado_agendamento_id->setSize('100%');

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>
        $row1 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'), $pessoa_id], [new TLabel("Status:", null, '14px', null, '100%'), $estado_agendamento_id]);
        $row1->layout = ['col-sm-6', 'col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Data/hora de início:", null, '14px', null, '100%'), $inicio, new TLabel("até", null, '14px', null), $inicio_ate], [new TLabel("Data/Hora fim:", null, '14px', null, '100%'), $fim, new TLabel("até", null, '14px', null), $fim_ate]);
        $row2->layout = ['col-sm-6', 'col-sm-6'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // keep the form filled during navigation with session data
        $this->form->setData(TSession::getValue(__CLASS__ . '_filter_data'));

        $startHidden = true;

        if (TSession::getValue('AgendamentoList_expand_start_hidden') === false) {
            $startHidden = false;
        } elseif (TSession::getValue('AgendamentoList_expand_start_hidden') === true) {
            $startHidden = true;
        }
        $expandButton = $this->form->addExpandButton("Filtros", 'fas:filter #000000', $startHidden);
        $expandButton->addStyleClass('btn-default');
        $expandButton->setAction(new TAction([$this, 'onExpandForm'], ['static' => 1]), "Filtros");
        $this->form->addField($expandButton);

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary');

        $btn_onshow = $this->form->addAction("Cadastrar", new TAction(['AgendamentoForm', 'onShow']), 'fas:plus #69aa46');
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
        $column_pessoa_nome = new TDataGridColumn('pessoa->nome', "Cliente", 'left');
        $column_inicio_transformed = new TDataGridColumn('inicio', "Data/hora de início", 'left');
        $column_fim_transformed = new TDataGridColumn('fim', "Data/Hora fim", 'left');
        $column_valor_transformed = new TDataGridColumn('valor', "Valor", 'right');
        $column_estado_agendamento_label = new TDataGridColumn('estado_agendamento->label', "Status", 'left');

        $column_inicio_transformed->setTransformer(function ($value, $object, $row, $cell = null, $last_row = null) {
            if (!empty(trim((string) $value))) {
                try {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                } catch (Exception $e) {
                    return $value;
                }
            }
        });

        $column_fim_transformed->setTransformer(function ($value, $object, $row, $cell = null, $last_row = null) {
            if (!empty(trim((string) $value))) {
                try {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                } catch (Exception $e) {
                    return $value;
                }
            }
        });

        $column_valor_transformed->setTransformer(function ($value, $object, $row, $cell = null, $last_row = null) {
            if (!$value) {
                $value = 0;
            }

            if (is_numeric($value)) {
                return "R$ " . number_format($value, 2, ",", ".");
            } else {
                return $value;
            }
        });

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        //<onBeforeColumnsCreation>

        //</onBeforeColumnsCreation>

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_pessoa_nome);
        $this->datagrid->addColumn($column_inicio_transformed);
        $this->datagrid->addColumn($column_fim_transformed);
        $this->datagrid->addColumn($column_valor_transformed);
        $this->datagrid->addColumn($column_estado_agendamento_label);

        //<onAfterColumnsCreation>

        //</onAfterColumnsCreation>

        $action_onEdit = new TDataGridAction(array('AgendamentoForm', 'onEdit'));
        $action_onEdit->setUseButton(false);
        $action_onEdit->setButtonClass('btn btn-default btn-sm');
        $action_onEdit->setLabel("Editar");
        $action_onEdit->setImage('far:edit #478fca');
        $action_onEdit->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onEdit);

        $action_onDelete = new TDataGridAction(array('AgendamentoList', 'onDelete'));
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
        $button_cadastrar->setAction(new TAction(['AgendamentoForm', 'onShow']), "Cadastrar");
        $button_cadastrar->addStyleClass('btn-default');
        $button_cadastrar->setImage('fas:plus #69AA46');

        $this->datagrid_form->addField($button_cadastrar);

        $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #2d3436');
        $dropdown_button_exportar->setPullSide('right');
        $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown_button_exportar->addPostAction("XLS", new TAction(['AgendamentoList', 'onExportXls'], ['static' => 1]), 'datagrid_' . self::$formName, 'fas:file-excel #4CAF50');
        $dropdown_button_exportar->addPostAction("PDF", new TAction(['AgendamentoList', 'onExportPdf'], ['static' => 1]), 'datagrid_' . self::$formName, 'far:file-pdf #e74c3c');

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
                $object = new Agendamento($key, FALSE); //</blockLine>

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

        if (isset($data->pessoa_id) and ((is_scalar($data->pessoa_id) and $data->pessoa_id !== '') or (is_array($data->pessoa_id) and (!empty($data->pessoa_id))))) {

            $filters[] = new TFilter('pessoa_id', '=', $data->pessoa_id); // create the filter 
        }

        if (isset($data->estado_agendamento_id) and ((is_scalar($data->estado_agendamento_id) and $data->estado_agendamento_id !== '') or (is_array($data->estado_agendamento_id) and (!empty($data->estado_agendamento_id))))) {

            $filters[] = new TFilter('estado_agendamento_id', '=', $data->estado_agendamento_id); // create the filter 
        }

        if (isset($data->inicio) and ((is_scalar($data->inicio) and $data->inicio !== '') or (is_array($data->inicio) and (!empty($data->inicio))))) {

            $filters[] = new TFilter('inicio', '>=', $data->inicio); // create the filter 
        }

        if (isset($data->inicio_ate) and ((is_scalar($data->inicio_ate) and $data->inicio_ate !== '') or (is_array($data->inicio_ate) and (!empty($data->inicio_ate))))) {

            $filters[] = new TFilter('inicio', '<=', $data->inicio_ate); // create the filter 
        }

        if (isset($data->fim) and ((is_scalar($data->fim) and $data->fim !== '') or (is_array($data->fim) and (!empty($data->fim))))) {

            $filters[] = new TFilter('fim', '>=', $data->fim); // create the filter 
        }

        if (isset($data->fim_ate) and ((is_scalar($data->fim_ate) and $data->fim_ate !== '') or (is_array($data->fim_ate) and (!empty($data->fim_ate))))) {

            $filters[] = new TFilter('fim', '<=', $data->fim_ate); // create the filter 
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

            // creates a repository for Agendamento
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

            if (TSession::getValue('AgendamentoList_expand_start_hidden') === false) {
                TSession::setValue('AgendamentoList_expand_start_hidden', true);
            } elseif (TSession::getValue('AgendamentoList_expand_start_hidden') === true) {
                TSession::setValue('AgendamentoList_expand_start_hidden', false);
            } else {
                TSession::setValue('AgendamentoList_expand_start_hidden', !$startHidden);
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

        $object = new Agendamento($id);

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
