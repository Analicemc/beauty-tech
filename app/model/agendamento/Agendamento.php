<?php

//<fileHeader>

//</fileHeader>

class Agendamento extends TRecord
{
    const TABLENAME  = 'agendamento';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'max'; // {max, serial}



    const DELETEDAT  = 'deleted_at';
    const CREATEDAT  = 'created_at';
    const UPDATEDAT  = 'updated_at';


    private $pessoa;
    private $estado_agendamento;
    private $agendamento_origem;

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
        parent::addAttribute('agendamento_origem_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('estado_agendamento_id');
        parent::addAttribute('inicio');
        parent::addAttribute('fim');
        parent::addAttribute('obs');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
        parent::addAttribute('deleted_at');
        //<onAfterConstruct>

        //</onAfterConstruct>
    }

    /**
     * Method set_pessoa
     * Sample of usage: $var->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }

    /**
     * Method get_pessoa
     * Sample of usage: $var->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {

        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);

        // returns the associated object
        return $this->pessoa;
    }
    /**
     * Method set_estado_agendamento
     * Sample of usage: $var->estado_agendamento = $object;
     * @param $object Instance of EstadoAgendamento
     */
    public function set_estado_agendamento(EstadoAgendamento $object)
    {
        $this->estado_agendamento = $object;
        $this->estado_agendamento_id = $object->id;
    }

    /**
     * Method get_estado_agendamento
     * Sample of usage: $var->estado_agendamento->attribute;
     * @returns EstadoAgendamento instance
     */
    public function get_estado_agendamento()
    {

        // loads the associated object
        if (empty($this->estado_agendamento))
            $this->estado_agendamento = new EstadoAgendamento($this->estado_agendamento_id);

        // returns the associated object
        return $this->estado_agendamento;
    }
    /**
     * Method set_agendamento
     * Sample of usage: $var->agendamento = $object;
     * @param $object Instance of Agendamento
     */
    public function set_agendamento_origem(Agendamento $object)
    {
        $this->agendamento_origem = $object;
        $this->agendamento_origem_id = $object->id;
    }

    /**
     * Method get_agendamento_origem
     * Sample of usage: $var->agendamento_origem->attribute;
     * @returns Agendamento instance
     */
    public function get_agendamento_origem()
    {

        // loads the associated object
        if (empty($this->agendamento_origem))
            $this->agendamento_origem = new Agendamento($this->agendamento_origem_id);

        // returns the associated object
        return $this->agendamento_origem;
    }

    /**
     * Method getProcedimentoAgendamentos
     */
    public function getProcedimentoAgendamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('agendamento_id', '=', $this->id));
        return ProcedimentoAgendamento::getObjects($criteria);
    }
    /**
     * Method getAgendamentos
     */
    public function getAgendamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('agendamento_origem_id', '=', $this->id));
        return Agendamento::getObjects($criteria);
    }
    /**
     * Method getAnexoAgendamentos
     */
    public function getAnexoAgendamentos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('agendamento_id', '=', $this->id));
        return AnexoAgendamento::getObjects($criteria);
    }
    /**
     * Method getContas
     */
    public function getContas()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('agendamento_id', '=', $this->id));
        return Conta::getObjects($criteria);
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

        $values = ProcedimentoAgendamento::where('agendamento_id', '=', $this->id)->getIndexedArray('agendamento_id', '{procedimento->nome}');
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

        $values = ProcedimentoAgendamento::where('agendamento_id', '=', $this->id)->getIndexedArray('procedimento_id', '{procedimento->nome}');
        return implode(', ', $values);
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

        $values = Agendamento::where('agendamento_origem_id', '=', $this->id)->getIndexedArray('agendamento_origem_id', '{agendamento_origem->id}');
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

        $values = Agendamento::where('agendamento_origem_id', '=', $this->id)->getIndexedArray('pessoa_id', '{pessoa->nome}');
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

        $values = Agendamento::where('agendamento_origem_id', '=', $this->id)->getIndexedArray('estado_agendamento_id', '{estado_agendamento->nome}');
        return implode(', ', $values);
    }


    public function set_anexo_agendamento_agendamento_to_string($anexo_agendamento_agendamento_to_string)
    {
        if (is_array($anexo_agendamento_agendamento_to_string)) {
            $values = Agendamento::where('id', 'in', $anexo_agendamento_agendamento_to_string)->getIndexedArray('id', 'id');
            $this->anexo_agendamento_agendamento_to_string = implode(', ', $values);
        } else {
            $this->anexo_agendamento_agendamento_to_string = $anexo_agendamento_agendamento_to_string;
        }

        $this->vdata['anexo_agendamento_agendamento_to_string'] = $this->anexo_agendamento_agendamento_to_string;
    }

    public function get_anexo_agendamento_agendamento_to_string()
    {
        if (!empty($this->anexo_agendamento_agendamento_to_string)) {
            return $this->anexo_agendamento_agendamento_to_string;
        }

        $values = AnexoAgendamento::where('agendamento_id', '=', $this->id)->getIndexedArray('agendamento_id', '{agendamento->id}');
        return implode(', ', $values);
    }


    public function set_conta_pessoa_to_string($conta_pessoa_to_string)
    {
        if (is_array($conta_pessoa_to_string)) {
            $values = Pessoa::where('id', 'in', $conta_pessoa_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_pessoa_to_string = implode(', ', $values);
        } else {
            $this->conta_pessoa_to_string = $conta_pessoa_to_string;
        }

        $this->vdata['conta_pessoa_to_string'] = $this->conta_pessoa_to_string;
    }

    public function get_conta_pessoa_to_string()
    {
        if (!empty($this->conta_pessoa_to_string)) {
            return $this->conta_pessoa_to_string;
        }

        $values = Conta::where('agendamento_id', '=', $this->id)->getIndexedArray('pessoa_id', '{pessoa->nome}');
        return implode(', ', $values);
    }


    public function set_conta_tipo_conta_to_string($conta_tipo_conta_to_string)
    {
        if (is_array($conta_tipo_conta_to_string)) {
            $values = TipoConta::where('id', 'in', $conta_tipo_conta_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_tipo_conta_to_string = implode(', ', $values);
        } else {
            $this->conta_tipo_conta_to_string = $conta_tipo_conta_to_string;
        }

        $this->vdata['conta_tipo_conta_to_string'] = $this->conta_tipo_conta_to_string;
    }

    public function get_conta_tipo_conta_to_string()
    {
        if (!empty($this->conta_tipo_conta_to_string)) {
            return $this->conta_tipo_conta_to_string;
        }

        $values = Conta::where('agendamento_id', '=', $this->id)->getIndexedArray('tipo_conta_id', '{tipo_conta->nome}');
        return implode(', ', $values);
    }


    public function set_conta_categoria_to_string($conta_categoria_to_string)
    {
        if (is_array($conta_categoria_to_string)) {
            $values = Categoria::where('id', 'in', $conta_categoria_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_categoria_to_string = implode(', ', $values);
        } else {
            $this->conta_categoria_to_string = $conta_categoria_to_string;
        }

        $this->vdata['conta_categoria_to_string'] = $this->conta_categoria_to_string;
    }

    public function get_conta_categoria_to_string()
    {
        if (!empty($this->conta_categoria_to_string)) {
            return $this->conta_categoria_to_string;
        }

        $values = Conta::where('agendamento_id', '=', $this->id)->getIndexedArray('categoria_id', '{categoria->nome}');
        return implode(', ', $values);
    }


    public function set_conta_forma_pagamento_to_string($conta_forma_pagamento_to_string)
    {
        if (is_array($conta_forma_pagamento_to_string)) {
            $values = FormaPagamento::where('id', 'in', $conta_forma_pagamento_to_string)->getIndexedArray('nome', 'nome');
            $this->conta_forma_pagamento_to_string = implode(', ', $values);
        } else {
            $this->conta_forma_pagamento_to_string = $conta_forma_pagamento_to_string;
        }

        $this->vdata['conta_forma_pagamento_to_string'] = $this->conta_forma_pagamento_to_string;
    }

    public function get_conta_forma_pagamento_to_string()
    {
        if (!empty($this->conta_forma_pagamento_to_string)) {
            return $this->conta_forma_pagamento_to_string;
        }

        $values = Conta::where('agendamento_id', '=', $this->id)->getIndexedArray('forma_pagamento_id', '{forma_pagamento->nome}');
        return implode(', ', $values);
    }


    public function set_conta_agendamento_to_string($conta_agendamento_to_string)
    {
        if (is_array($conta_agendamento_to_string)) {
            $values = Agendamento::where('id', 'in', $conta_agendamento_to_string)->getIndexedArray('id', 'id');
            $this->conta_agendamento_to_string = implode(', ', $values);
        } else {
            $this->conta_agendamento_to_string = $conta_agendamento_to_string;
        }

        $this->vdata['conta_agendamento_to_string'] = $this->conta_agendamento_to_string;
    }

    public function get_conta_agendamento_to_string()
    {
        if (!empty($this->conta_agendamento_to_string)) {
            return $this->conta_agendamento_to_string;
        }

        $values = Conta::where('agendamento_id', '=', $this->id)->getIndexedArray('agendamento_id', '{agendamento->id}');
        return implode(', ', $values);
    }

    /**
     * Method onBeforeDelete
     */
    public function onBeforeDelete()
    {
        //<onBeforeDeleteCode>

        //</onBeforeDeleteCode>

        if (ProcedimentoAgendamento::where('agendamento_id', '=', $this->id)->first()) {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }

        if (Agendamento::where('agendamento_origem_id', '=', $this->id)->first()) {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }

        if (AnexoAgendamento::where('agendamento_id', '=', $this->id)->first()) {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }

        if (Conta::where('agendamento_id', '=', $this->id)->first()) {
            throw new Exception("Não é possível deletar este registro pois ele está sendo utilizado em outra parte do sistema");
        }
    }

    //<userCustomFunctions>
    public function onAfterStore()
    {
        $estado = EstadoAgendamento::find($this->estado_agendamento_id);

        if ($estado && $estado->final == 'T') {
            TApplication::loadPage('AgendamentoContasForm', 'onEdit', ['key' => $this->id, 'agendamento_id' => $this->id]);
        }
    }

    public function get_valor()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('agendamento_id', '=', $this->id));
        $criteria->add(new TFilter('valor_total', 'is not', null));

        $rep = new TRepository('ProcedimentoAgendamento');

        return $rep->sumBy('valor_total');
    }
    //</userCustomFunctions>
}
