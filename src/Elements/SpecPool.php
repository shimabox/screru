<?php

namespace SMB\Screru\Elements;

use SMB\Screru\Elements\Spec;

/**
 * SpecPool
 */
class SpecPool
{
    /**
     * Spec
     * @var array [\SMB\Screru\Elements\Spec]
     */
    private $spec = array();

    /**
     * Spec 追加
     * @param \SMB\Screru\Elements\Spec $spec
     * @return \SMB\Screru\Elements\SpecPool
     */
    public function addSpec(Spec $spec)
    {
        $this->spec[] = $spec;
        return $this;
    }

    /**
     * Spec ゲッター
     * @return array [\SMB\Screru\Elements\Spec]
     */
    public function getSpec()
    {
        return $this->spec;
    }

    /**
     * Spec clear
     */
    public function clearSpec()
    {
        $this->spec = [];
    }
}
