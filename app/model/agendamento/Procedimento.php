<?php

//<fileHeader>

//</fileHeader>

class Procedimento extends TRecord
{
    const TABLENAME  = 'procedimento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}





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
        parent::addAttribute('nome');
        parent::addAttribute('valor');
        parent::addAttribute('tempo_estimado');
        parent::addAttribute('ativo');
        //<onAfterConstruct>

        //</onAfterConstruct>
    }


    /**
     * Method getProcedimentoAgendamentos
     */
    public function getProcedimentoAgendamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('procedimento_id', '=', $this->id));
        return ProcedimentoAgendamento::getObjects($criteria);
    }


    public function set_procedimento_agendamento_agendamento_to_string($procedimento_agendamento_agendamento_to_string)
    {
        if (is_array($procedimento_agendamento_agendamento_to_string)) {
            $values = Agendamento::where('id', 'in', $procedimento_agendamento_agendamento_to_string)->getIndexedArray('id', 'id');
            $this->procedimento_agendamento_agendamento_to_string = implode(', ', $values);
        } else {
            $this->procedimento_agendamento_agendamento_to_string = $procedimento_agendamento_agendamento_to_string;
        }

        $this->vdata['procedimento_agendamento_agendamento_to_string'] = $this->procedimento_agendamento_agendamento_to_string;
    }

    public function get_procedimento_agendamento_agendamento_to_string()
    {
        if (!empty($this->procedimento_agendamento_agendamento_to_string)) {
            return $this->procedimento_agendamento_agendamento_to_string;
        }

        $values = ProcedimentoAgendamento::where('procedimento_id', '=', $this->id)->getIndexedArray('agendamento_id', '{agendamento->id}');
        return implode(', ', $values);
    }


    public function set_procedimento_agendamento_procedimento_to_string($procedimento_agendamento_procedimento_to_string)
    {
        if (is_array($procedimento_agendamento_procedimento_to_string)) {
            $values = Procedimento::where('id', 'in', $procedimento_agendamento_procedimento_to_string)->getIndexedArray('nome', 'nome');
            $this->procedimento_agendamento_procedimento_to_string = implode(', ', $values);
        } else {
            $this->procedimento_agendamento_procedimento_to_string = $procedimento_agendamento_procedimento_to_string;
        }

        $this->vdata['procedimento_agendamento_procedimento_to_string'] = $this->procedimento_agendamento_procedimento_to_string;
    }

    public function get_procedimento_agendamento_procedimento_to_string()
    {
        if (!empty($this->procedimento_agendamento_procedimento_to_string)) {
            return $this->procedimento_agendamento_procedimento_to_string;
        }

        $values = ProcedimentoAgendamento::where('procedimento_id', '=', $this->id)->getIndexedArray('procedimento_id', '{procedimento->nome}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
        //<onBeforeDeleteCode>

        //</onBeforeDeleteCode>

        if (ProcedimentoAgendamento::where('procedimento_id', '=', $this->id)->first()) {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    }

    //<userCustomFunctions>

    //</userCustomFunctions>
}
