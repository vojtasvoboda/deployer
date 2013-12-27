<?php
namespace Inspirio\Deployer;

use Inspirio\Deployer\Module\ModuleBagInterface;
use Inspirio\Deployer\Module\ModuleInterface;
use Symfony\Component\HttpFoundation\Request;

class ModuleRenderer {

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Renders the module.
     *
     * @param Request            $request
     * @param ModuleBagInterface $bag
     * @param ModuleInterface    $module
     *
     * @throws \RuntimeException
     * @return string
     */
    public function renderModule(Request $request, ModuleBagInterface $bag, ModuleInterface $module)
    {
        $this->request = $request;

        $template = $bag->getTemplateLayout();

        $data['module']  = $module;
        $data['request'] = $request;

        $content = $this->twig->render($template, $data);

        $this->request = null;

        return $content;
    }

    /**
     * Renders the module.
     *
     * @param ModuleInterface $module
     *
     * @throws \RuntimeException
     * @return string
     */
    public function subRenderModule(ModuleInterface $module)
    {
        if ($this->request === null) {
            throw new \RuntimeException("Can't sub-render module {$module}, call the renderModule method first");
        }

        $data = $module->render($this->twig, $this->request);

        // rendered content returned
        if (is_scalar($data)) {
            return $data;
        }

        // invalid content
        if (!is_array($data)) {
            $hint = is_object($data) ? get_class($data) : gettype($data);
            throw new \RuntimeException("Module {$module->getName()} render returned {$hint}, string or array expected.");
        }

        // view data returned
        $data['module']  = $module;
        $data['request'] = $this->request;

        $classRefl = new \ReflectionObject($module);
        $classDir  = dirname($classRefl->getFileName());

        $template = lcfirst(implode('', array_map('ucfirst', explode('_', $module->getName()))));
        $template  = $classDir .'/view/'. $template .'.twig';

        return $this->twig->render($template, $data);
    }
}
