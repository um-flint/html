<?php

namespace UMFlint\Html\Traits;

use Illuminate\Support\Collection;

trait HasAttributes
{
    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * Create new collection for attributes.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     */
    protected function initAttributes()
    {
        $this->attributes = new Collection();
    }

    /**
     * Set attributes on element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string|array $attribute
     * @param string       $value
     * @return $this
     */
    public function set($attribute, $value = '')
    {
        if (is_array($attribute)) {
            foreach ($attribute as $key => $value) {
                $this->attributes->put($key, $value);
            }
        }else {
            $this->attributes->put($attribute, $value);
        }

        return $this;
    }

    /**
     * Get an attribute.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param $attribute
     * @return mixed
     */
    public function get($attribute)
    {
        return $this->attributes->get($attribute, null);
    }

    /**
     * Get all attributes.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Remove an attribute from the element.
     *
     * @author Donald Wilcox <dowilcox@umflint.edu>
     * @param string|array $attribute
     * @return $this
     */
    public function remove($attribute)
    {
        $this->attributes->forget($attribute);

        return $this;
    }
}