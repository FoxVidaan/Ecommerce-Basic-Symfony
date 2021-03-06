<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\DataTransformer\CentimesTransformer;
use App\Form\Type\PriceType;
use PhpParser\Node\Expr\New_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'placeholder' => 'Veuillez indiquer le nom du produit'
                ],
                'required' => false,
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => [
                    'placeholder' => 'Veuillez indiquer une courte description du produit'
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr' => [
                    'placeholder' => 'Veuillez indiquer le prix du produit en €'
                ],
                'required' => false,
                'divisor' => 100
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => [
                    'placeholder' => 'Tapez une url d\'image'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
            ]);

//        $builder->get('price')->addModelTransformer(new CentimesTransformer());

//        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
//            /** @var Product $product */
//            $product = $event->getData();
//
//            if ($product->getPrice() !== null) {
//                $product->setPrice($product->getPrice()*100);
//            }
//        });

//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $form = $event->getForm();
//
//            /** @var Product $product */
//            $product = $event->getData();
//
//            if ($product->getPrice() !== null) {
//                $product->setPrice($product->getPrice()/100);
//            }
//
//            /*if ($product->getId() == null) {
//                $form->add('category', EntityType::class, [
//                    'label' => 'Catégorie',
//                    'placeholder' => '-- Choisir une catégorie --',
//                    'class' => Category::class,
//                    'choice_label' => function (Category $category) {
//                        return strtoupper($category->getName());
//                    }
//                ]);
//            }*/
//        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
