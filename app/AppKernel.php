<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            new BW\BlogBundle\BWBlogBundle(),
            new BW\MainBundle\BWMainBundle(),
            new BW\UserBundle\BWUserBundle(),
            new BW\MenuBundle\BWMenuBundle(),
            new BW\LocalizationBundle\BWLocalizationBundle(),
            new BW\RouterBundle\BWRouterBundle(),
            new BW\MailingBundle\BWMailingBundle(),
            new BW\SkeletonBundle\BWSkeletonBundle(),
            new BW\BreadcrumbsBundle\BWBreadcrumbsBundle(),
            new BW\SliderBundle\BWSliderBundle(),
            new BW\GalleryBundle\BWGalleryBundle(),
            new BW\ShopBundle\BWShopBundle(),
            new BW\UploadBundle\BWUploadBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
