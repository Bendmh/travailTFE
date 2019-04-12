<?php
/**
 * Created by PhpStorm.
 * User: B
 * Date: 23-02-19
 * Time: 10:32
 */

namespace App\Entity;

class ResultSearch{

    private $classe;

    /**
     * @return mixed
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * @param mixed $classe
     * @return ResultSearch
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;
        return $this;
    }

    private $matiere;

    /**
     * @return mixed
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * @param mixed $matiere
     * @return ResultSearch
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;
        return $this;
    }


}