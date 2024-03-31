<?php

namespace App\Controller\Admin;

use App\Form\SiteSettingsType;
use App\Repository\SettingsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

class SystemConfigurationController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    #[Route('/system/configuration', name: 'system_configuration')]
    public function index(Request $request, SettingsRepository $settingsRepository, KernelInterface $kernel): Response
    {
        $settings = $settingsRepository->findOneBy([]);

        $faviconFileName = $settings->getFavicon() ?: "favicon.png";
        if ($settings->getFavicon()) {
            $settings->setFavicon(new File($settings->getFavicon()));
        }

        $form = $this->createForm(SiteSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $favicon = $form->get('favicon')->getData();
            $fs = new Filesystem();

            if ($favicon instanceof UploadedFile) {
                $projectDir = $kernel->getProjectDir();
                $newFav = $favicon->move($projectDir . '/public/', $faviconFileName);
                $fs->copy($newFav, 'apple-touch-icon.png', true);
                $source = $newFav;
                $destination = 'favicon.ico';
                if (file_exists($projectDir . '/public/favicon.ico')) {
                    unlink($projectDir . '/public/favicon.ico');
                }
                $sizes = array(
                    array( 16, 16 ),
                    array( 24, 24 ),
                    array( 32, 32 ),
                    array( 48, 48 ),
                    array( 64, 64 ),
                    array( 128, 128 ),
                    array( 192, 192 ),
                    array( 256, 256 ),
                );
                $ico_lib = new \PHP_ICO($source, $sizes);
                if ($ico_lib->save_ico($destination) == false) {
                    throw new \Exception("the favicon.ico file hasn't been saved successfully");
                }

                $settings->setFavicon($faviconFileName);
            }

            $em->persist($settings);
            $em->flush();

            return $this->redirectToRoute('admin_system_configuration');
        }
        return $this->render('admin/system_configuration.twig', [
            'form' => $form->createView(),
        ]);
    }
}
