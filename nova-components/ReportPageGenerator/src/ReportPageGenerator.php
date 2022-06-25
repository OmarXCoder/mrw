<?php

namespace Mrw\ReportPageGenerator;

use Laravel\Nova\ResourceTool;

class ReportPageGenerator extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Report Page Generator';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'report-page-generator';
    }
}
