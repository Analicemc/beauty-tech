<?php

//<fileHeader>

//</fileHeader>

class EstadoAgendamento extends TRecord
{
    const TABLENAME  = 'estado_agendamento';
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
        parent::addAttribute('inicial');
        parent::addAttribute('final');
        parent::addAttribute('cor');
        //<onAfterConstruct>

        //</onAfterConstruct>
    }


    /**
     * Method getAgendamentos
     */
    public function getAgendamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('estado_agendamento_id', '=', $this->id));
        return Agendamento::getObjects($criteria);
    }


    public function set_agendamento_agendamento_origem_to_string($agendamento_agendamento_origem_to_string)
    {
        if (is_array($agendamento_agendamento_origem_to_string)) {
            $values = Agendamento::where('id', 'in', $agendamento_agendamento_origem_to_string)->getIndexedArray('id', 'id');
            $this->agendamento_agendamento_origem_to_string = implode(', ', $values);
        } else {
            $this->agendamento_agendamento_origem_to_string = $agendamento_agendamento_origem_to_string;
        }

        $this->vdata['agendamento_agendamento_origem_to_string'] = $this->agendamento_agendamento_origem_to_string;
    }

    public function get_agendamento_agendamento_origem_to_string()
    {
        if (!empty($this->agendamento_agendamento_origem_to_string)) {
            return $this->agendamento_agendamento_origem_to_string;
        }

        $values = Agendamento::where('estado_agendamento_id', '=', $this->id)->getIndexedArray('agendamento_origem_id', '{agendamento_origem->id}');
        return implode(', ', $values);
    }


    public function set_agendamento_pessoa_to_string($agendamento_pessoa_to_string)
    {
        if (is_array($agendamento_pessoa_to_string)) {
            $values = Pessoa::where('id', 'in', $agendamento_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->agendamento_pessoa_to_string = implode(', ', $values);
        } else {
            $this->agendamento_pessoa_to_string = $agendamento_pessoa_to_string;
        }

        $this->vdata['agendamento_pessoa_to_string'] = $this->agendamento_pessoa_to_string;
    }

    public function get_agendamento_pessoa_to_string()
    {
        if (!empty($this->agendamento_pessoa_to_string)) {
            return $this->agendamento_pessoa_to_string;
        }

        $values = Agendamento::where('estado_agendamento_id', '=', $this->id)->getIndexedArray('pessoa_id', '{pessoa->nome}');
        return implode(', ', $values);
    }


    public function set_agendamento_estado_agendamento_to_string($agendamento_estado_agendamento_to_string)
    {
        if (is_array($agendamento_estado_agendamento_to_string)) {
            $values = EstadoAgendamento::where('id', 'in', $agendamento_estado_agendamento_to_string)->getIndexedArray('nome', 'nome');
            $this->agendamento_estado_agendamento_to_string = implode(', ', $values);
        } else {
            $this->agendamento_estado_agendamento_to_string = $agendamento_estado_agendamento_to_string;
        }

        $this->vdata['agendamento_estado_agendamento_to_string'] = $this->agendamento_estado_agendamento_to_string;
    }

    public function get_agendamento_estado_agendamento_to_string()
    {
        if (!empty($this->agendamento_estado_agendamento_to_string)) {
            return $this->agendamento_estado_agendamento_to_string;
        }

        $values = Agendamento::where('estado_agendamento_id', '=', $this->id)->getIndexedArray('estado_agendamento_id', '{estado_agendamento->nome}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
        //<onBeforeDeleteCode>

        //</onBeforeDeleteCode>

        if (Agendamento::where('estado_agendamento_id', '=', $this->id)->first()) {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    }

    //<userCustomFunctions>
    public function get_label()
    {
        return "<span class='state-label' style='background-color: {$this->cor}'>{$this->nome}</span>";
    }
    //</userCustomFunctions>
}
