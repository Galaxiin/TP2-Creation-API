<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Pret;
use App\Entity\Livre;
use App\Entity\Adherent;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   
    private $manager;
    private $faker;
    private $repolivre;
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->faker=Factory::create("fr-FR");
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->repolivre = $this->manager->getRepository(Livre::class);

        $this->load_adherent($manager);
        $this->load_pret($manager);
    }
    
    /**
     * Creation des adherents
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load_adherent(ObjectManager $manager)
    {
        $genre=['female','male'];
        $commune=[
            "78003","78005","78006","78007","78009","78010","78013","78015","78020",
            "78029","78030","78031","78033","78034","78036","78043",
        ];
        
        $adherentadmin = new Adherent();
        $rolesA[] = Adherent::ROLE_ADMIN;
        $adherentadmin->setNom("Raizer")
                    ->setPrenom("Antonin")
                    ->setTel("060504030201")
                    ->setMail("admin@gmail.com")
                    ->setPassword($this->encoder->encodePassword($adherentadmin, $adherentadmin->getNom()))
                    ->setRoles($rolesA);
        
        $this->manager->persist($adherentadmin);

        $adherentmanager = new Adherent();
        $rolesM[] = Adherent::ROLE_MANAGER;
        $adherentmanager->setNom("Depaix-Piettre")
                    ->setPrenom("Flora")
                    ->setTel("060504030201")
                    ->setMail("manager@gmail.com")
                    ->setPassword($this->encoder->encodePassword($adherentmanager, $adherentmanager->getNom()))
                    ->setRoles($rolesM);
        
        $this->manager->persist($adherentmanager);

        for ($i=0; $i <25; $i++) { 
            $adherent = new Adherent();

            $adherent->setNom($this->faker->lastName())
                    ->setPrenom($this->faker->firstName($genre[mt_rand(0,1)]))
                    ->setAdresse($this->faker->streetAddress())
                    ->setTel($this->faker->phoneNumber())
                    ->setCodeCommune($commune[mt_rand(0,sizeof($commune)-1)])
                    ->setMail(strtolower($adherent->getNom()).'@gmail.com')
                    ->setPassword($this->encoder->encodePassword($adherent, $adherent->getNom()));

            $this->addReference('adherent'.$i, $adherent);
            $this->manager->persist($adherent);
            $this->manager->flush();
        }
    }

    /**
     * Creation des prets
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load_pret(ObjectManager $manager)
    {
        for ($i=0; $i <25; $i++) {
            $max=mt_rand(1,5);
            for ($j=0; $j <$max; $j++) { 
                $pret = new Pret();
                
                $pret->setAdherent($this->getReference('adherent'.$i))
                    ->setLivre($this->repolivre->find(mt_rand(1,49)))
                    ->setDatePret($this->faker->dateTimeBetween("-6 months"));

                $dateRetourPrevue = date('Y-m-d H:m:n',strtotime('15 days',$pret->getDatePret()->getTimestamp()));
                $dateRetourPrevue=\DateTime::createFromFormat('Y-m-d H:m:n',$dateRetourPrevue);
                $pret->setDateRetourPrevue($dateRetourPrevue);

                if(mt_rand(1,3)==1) {
                    $pret->setDateRetourReelle($this->faker->dateTimeInInterval($pret->getDatePret(),"+30 days"));
                }
            
                $this->manager->persist($pret);
                $this->manager->flush();
            }
        }
    }
}
