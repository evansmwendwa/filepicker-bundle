<?php
namespace Evans\FilepickerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Evans\FilepickerBundle\Service\FileChooser;

class FilepickerType extends AbstractType
{
    protected $chooser;

    public function __construct(FileChooser $fileChooser) {
        $this->chooser = $fileChooser;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'enable_uploads' => false,
            'multi_select' => false,
            'autoload' => true
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['folders'] = $this->chooser->getFolders();
        $view->vars['baseUrl'] = $this->chooser->getBaseUrl();
    }

    public function getParent()
    {
        return TextType::class;
    }

}
