<?php

//<fileHeader>

//</fileHeader>

class AnexoAgendamento extends TRecord
{
    const TABLENAME  = 'anexo_agendamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}




    private $agendamento;

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
        parent::addAttribute('arquivo');
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



    //<userCustomFunctions>

    //</userCustomFunctions>
}
