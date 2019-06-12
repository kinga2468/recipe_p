<?php
/**
 * Recipe type.
 */

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\TagsDataTransformer;
use App\Repository\TagRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * Class RecipeType.
 */
class RecipeType extends AbstractType
{
    /**
     * Tags data transformer.
     *
     * @var \App\Form\DataTransformer\TagsDataTransformer|null
     */
    private $tagsDataTransformer = null;

    /**
     * RecipeType constructor.
     *
     * @param \App\Form\DataTransformer\TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
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
            'title',
            TextType::class,
            [
                'label' => 'label.title',
                'required' => true,
                'attr' => ['max_length' => 64],
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label' => 'label.description',
                'required' => true,
                'attr' => ['max_length' => 700],
            ]
        );

        $builder->add(
            'time',
            NumberType::class,
            [
                'label' => 'label.time',
                'required' => true,
                'scale' => 0,
            ]
        );
        $builder->add(
            'peopleAmount',
            NumberType::class,
            [
                'label' => 'label.peopleAmount',
                'required' => true,
                'scale' => 0,
            ]
        );

        $builder->add(
            'photo',
            FileType::class,
            [
                'label' => 'label.photo',
                'required' => true,
            ]
        );

//        $builder->add('photo', 'file', array(
//            'data_class' => 'Symfony\Component\HttpFoundation\File\File',
//            'property_path' => 'image',
//            'required' => false,
//        ));

        $builder->add('ingredients', CollectionType::class, [
            'entry_type' => IngredientType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
        ]);

        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label.tags',
                'required' => false,
                'attr' => [
                    'max_length' => 255,
                ],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
//        $resolver->setDefaults(['data_class' => 'Symfony\Component\HttpFoundation\File\File']);
        $resolver->setDefaults(['data_class' => Recipe::class]);
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
        return 'recipe';
    }
}
