<?php
namespace Evans\FilepickerBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class EvansPickerExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface $container Container interface
     */
    protected $container;

    /**
     * Initialize tinymce helper
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_Function('file_picker_init', [$this, 'filePickerInit'], ['is_safe' => ['html']]),
            new \Twig_Function('file_picker_init_css', [$this, 'initCss'], ['is_safe' => ['html']]),
        );
    }

    public function initCss() {
        return $this->get('templating')->render('EvansFilepickerBundle:css:injectcss.html.twig');
    }

    public function filePickerInit($options = [])
    {
        return $this->get('templating')->render('EvansFilepickerBundle:modal:modal.html.twig');
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
}
