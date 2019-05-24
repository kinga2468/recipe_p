<?php
/**
 * User type.
 */

namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\IngredientsDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer|null
     */
    private $tagsDataTransformer = null;

    /**
     * Ingredients data transformer.
     *
     * @var \App\Form\DataTransformer\IngredientsDataTransformer|null
     */
    private $ingredientsDataTransformer = null;

    /**
     * UserType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer, IngredientsDataTransformer $ingredientsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
        $this->ingredientsDataTransformer = $ingredientsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'label.email',
                'required' => true,
                'attr' => ['max_length' => 128],
            ]
        );
        $builder->add(
            'password',
            RepeatedType::class,
            [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'label.password'],
                'second_options' => ['label' => 'label.repeatPassword'],
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'firstName',
            TextType::class,
            [
                'label' => 'label.firstName',
                'required' => true,
                'attr' => ['max_length' => 50],
            ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
