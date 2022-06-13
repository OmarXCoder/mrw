<?php
namespace Mrw\ApiTokenGenerator;

use Laravel\Nova\ResourceTool;

class ApiTokenGenerator extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Api Token Generator';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'api-token-generator';
    }
}
