<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductsBrandsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands = [];
        $brandsName = ['Samsung', 'Apple', 'Google', 'Sony', 'Xiaomi'];
        // create 5 fake brands
        foreach ($brandsName as $brandName)
        {
            $brand = new Brand();
            $brand->setName($brandName);
            $manager->persist($brand);
            $brands[] = $brand;
        }

        $product = new Product();
        $product->setName('Galaxy Z Fold3 5G')
                ->setBrand($brands[0])
                ->setPrice(779)
                ->setDescription('Le Galaxy Fold3 5G  arrive dans une nouvelle version aboutie.
                Celle-ci propose le réel confort d’une tablette et son écran principal ( 120hz) devient aussi performant qu’un smartphone haut de marché.
                Son Flex Mode couplé au partage d’écran devient impressionnant quand il s’agit de l’utiliser de manière intensive.
                Ce troisième millésime prédit un succès.')
        ;
        $manager->persist($product);

        $product = new Product();
        $product->setName('Galaxy S21+ 5G')
                ->setBrand($brands[0])
                ->setPrice(229)
                ->setDescription('Samsung a vu encore plus grand pour son tout nouveau smartphone de la gamme S : un smartphone qui saura répondre à toutes vos attentes et bien plus.
                Aussi beau à l’intérieur qu’à l’extérieur, le Galaxy S21+ 5G est doté à la fois d’un design renversant et de performances exceptionnelles. Un processeur de qualité, Exynos 2100, ses 8 Go de RAM et un incroyable triple capteur photo sont intégrés pour satisfaire tous vos désirs. 
                C’est l’un des gros coups cœur pour ce début d’année 2021.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('iPhone 13')
                ->setBrand($brands[1])
                ->setPrice(859)
                ->setDescription('iPhone 13. Le double appareil photo le plus avancé à ce jour sur iPhone. La puce A15 Bionic, d’une vitesse fulgurante. Une autonomie nettement améliorée. Un design résistant. La 5G ultra-rapide et un écran Super Retina XDR de 6,1 ou 5,4 pouces plus lumineux.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('iPhone 13 mini')
                ->setBrand($brands[1])
                ->setPrice(759)
                ->setDescription('Phone 13 mini. Le double appareil photo le plus avancé à ce jour sur iPhone. La puce A15 Bionic, d’une vitesse fulgurante. Une autonomie nettement améliorée. Un design résistant. La 5G ultra-rapide et un écran Super Retina XDR de 5,4 pouces plus lumineux.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('Pixel 6 Pro')
                ->setBrand($brands[2])
                ->setPrice(889)
                ->setDescription('Pour son flagship premium, Google nous propose un smartphone d’une grande qualité. Avec un design unique en son genre, le Google 6 Pro offre à son utilisateur une prise en main très confortable.
                Son grand écran haut de gamme est très agréable à utiliser et son appareil photo a tout pour plaire. On finira par son nouveau processeur, le Google Tensor, conçu par Google, qui offre une grande puissance et plusieurs fonctionnalités innovantes.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('Pixel 6')
                ->setBrand($brands[2])
                ->setPrice(779)
                ->setDescription('En tant que successeur du très bon Pixel 5, Google nous propose un smartphone réussi avec des fonctionnalités uniques et innovantes. Avant toute chose, le Google Pixel 6 bénéficie d’un design original et finement travaillé. Pour la première fois, Google a équipé son smartphone d’un processeur fait maison, le Google Tensor. 
                Puissant et rapide, vous y découvrirez de nombreuses fonctionnalités appréciables comme la traduction instantanée de 55 langages.
                Pour finir, comme à son habitude, Google a équipé le Pixel 6 d’un appareil photo d’une grande qualité.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('Xperia 5 II 5G')
                ->setBrand($brands[3])
                ->setPrice(599)
                ->setDescription('La taille parfaite pour tenir dans votre main ou votre poche tout en intégrant les technologies Sony les plus récentes. Découvrez le Xperia 5 II.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('Xperia 10 III 5G')
                ->setBrand($brands[3])
                ->setPrice(429)
                ->setDescription('Le Xperia 10 III 5G est le nouveau smartphone milieu de gamme de Sony. Son  écran OLED de 6’’ au format 21:9 sera parfait pour profiter de vos films et  séries préférés.
                Grâce au processeur Snapdragon™ 690 5G vous profiterez  d’une expérience fluide, y compris en multitâche.
                Sa batterie grande capacité 4500mAh et ses trois capteurs photo sont également des points forts de ce smartphone Sony.')
        ;
        $manager->persist($product);
        
        $product = new Product();
        $product->setName('11T 5G')
                ->setBrand($brands[4])
                ->setPrice(479)
                ->setDescription('Avec le Xiaomi 11T 5G, le constructeur chinois débute fort sa rentrée 2021. Avec un design élégant et un écran ultra immersif, l’expérience utilisateur est très appréciable.
                Doté d’un processeur dernière génération et de 8 Go de RAM, ce smartphone saura répondre à tous vos besoins.
                Enfin, son triple objectif de 108 mégapixels, est idéal pour  capturer des clichés dignes d’un photographe professionnel.')
        ;
        $manager->persist($product);

        $product = new Product();
        $product->setName('11T Pro 5G')
                ->setBrand($brands[4])
                ->setPrice(699)
                ->setDescription('Pour sa rentrée de 2021 Xiaomi frappe fort. Il nous propose tout d’abord un 11T 5G très intéressant, mais son modèle au-dessus, le Xiaomi 11T Pro 5G est tout simplement fantastique.
                Entre ses performances époustouflantes, son écran ultra-immersif, son autonomie de très longue durée et son remarquable appareil photo, ce smartphone a tout pour plaire. Le Xiaomi 11T Pro 5G est le choix idéal pour toute personne à la recherche des meilleures performances du moment.')
        ;
        $manager->persist($product);
        $manager->flush();
    }
}
