<?php

//<fileHeader>

//</fileHeader>

class ProcedimentoAgendamento extends TRecord
{
    const TABLENAME  = 'procedimento_agendamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}




    private $agendamento;
    private $procedimento;

    //<classProperties>

    //</classProperties>

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        //<onBeforeConstruct>

        //</onBeforeConstruct>
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('agendamento_id');
        parent::addAttribute('procedimento_id');
        parent::addAttribute('qtde');
        parent::addAttribute('valor');
        parent::addAttribute('valor_total');
        //<onAfterConstruct>

        //</onAfterConstruct>
    }

    /**
     * Method set_agendamento
     * Sample of usage: $var->agendamento = $object;
     * @param $object Instance of Agendamento
     */
    public function set_agendamento(Agendamento $object)
    {
        $this->agendamento = $object;
        $this->agendamento_id = $object->id;
    }

    /**
     * Method get_agendamento
     * Sample of usage: $var->agendamento->attribute;
     * @returns Agendamento instance
     */
    public function get_agendamento()
    {

        // loads the associated object
        if (empty($this->agendamento))
            $this->agendamento = new Agendamento($this->agendamento_id);

        // returns the associated object
        return $this->agendamento;
    }
    /**
     * Method set_procedimento
     * Sample of usage: $var->procedimento = $object;
     * @param $object Instance of Procedimento
     */
    public function set_procedimento(Procedimento $object)
    {
        $this->procedimento = $object;
        $this->procedimento_id = $object->id;
    }

    /**
     * Method get_procedimento
     * Sample of usage: $var->procedimento->attribute;
     * @returns Procedimento instance
     */
    public function get_procedimento()
    {

        // loads the associated object
        if (empty($this->procedimento))
            $this->procedimento = new Procedimento($this->procedimento_id);

        // returns the associated object
        return $this->procedimento;
    }



    //<userCustomFunctions>

    //</userCustomFunctions>
}
