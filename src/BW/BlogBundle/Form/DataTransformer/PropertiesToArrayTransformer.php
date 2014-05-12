<?php

namespace BW\BlogBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\TaskBundle\Entity\Issue;

class PropertiesToArrayTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($properties)
    {
        if (null === $properties) {
            return "";
        }

        return $properties->toArray();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($array)
    {
        if ( ! $array) {
            return null;
        }

        $properties = $this->om
            ->getRepository('BWBlogBundle:CustomFieldProperty')
            ->findAll()
        ;

//        if (null === $properties) {
//            throw new TransformationFailedException(sprintf(
//                'An issue with number "%s" does not exist!',
//                $array
//            ));
//        }

        return 'qqqqq';
        return $properties;
    }
}