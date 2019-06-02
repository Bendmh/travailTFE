<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityType;
use App\Entity\CSV;
use App\Entity\Questions;
use App\Entity\QuestionsGroupes;
use App\Entity\QuestionsReponses;
use App\Form\CSVExportType;
use App\Form\CSVselectAssociationType;
use App\Form\CSVselectType;
use App\Form\CSVType;
use App\Repository\ActivityRepository;
use App\Repository\ActivityTypeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CSVController
 * @package App\Controller
 *
 * Cette classe concerne l'import et export de fichiers CSV
 */
class CSVController extends AbstractController
{
    /**
     * Route permettant d'enregistrer le fichier CSV dans la base de données. Ce choix m'a permis plus facilement d'associer les
     * colonnes du fichiers aux données des activités
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \League\Csv\Exception
     * @Route("/import/csv", name="import_csv")
     */
    public function csvImport(Request $request, ObjectManager $manager){

        $csv = new CSV();
        $form = $this->createForm(CSVType::class, $csv);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($csv);
            $manager->flush();

            //Ce switch permet de gérer les imports qui sont implémentés ou non
            switch ($csv->getType()->getName()){
                case ActivityType::QCM_ACTIVITY :
                case ActivityType::ASSOCIATION_ACTIVITY :
                    return $this->redirectToRoute('import_csv_id', ['id' => $csv->getId()]);
                    break;
                default :
                    $this->addFlash('error', 'Ce type d\'activité n\'est pas encore implémenté');
                    return $this->redirectToRoute('import_csv');
            }
        }

        return $this->render('csv/step1.html.twig', [
            'form_csv' => $form->createView(),
            'current_menu' => 'csv'
        ]);
    }


    /**
     * Route permettant de vérifier le tableau CSV ainsi que d'envoyer le formulaire correspondant à l'import
     *
     * @param null $id
     * @param CSV $csv
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \League\Csv\Exception
     * @Route("/import/csv/{id}", name="import_csv_id")
     */
    public function csvImportId($id = null, CSV $csv, Request $request, ObjectManager $manager){

        $projectDir = $this->getParameter('kernel.project_dir');
        $csvPath = $projectDir . "\public\csv\\" . $csv->getFile();
        $csvReturn = Reader::createFromPath($csvPath, 'r');
        $csvReturn->setHeaderOffset(0);
        $csvReturn->setDelimiter(';');
        $header = $csvReturn->getHeader();
        $size = sizeof($header);
        switch ($size){
            case 7 :
                $csv->setColumn7($header[6]);
            case 6 :
                $csv->setColumn6($header[5]);
            case 5 :
                $csv->setColumn5($header[4]);
            case 4 :
                $csv->setColumn4($header[3]);
            case 3 :
                $csv->setColumn3($header[2]);
            case 2 :
                $csv->setColumn2($header[1]);
            case 1 :
                $csv->setColumn1($header[0]);
                break;
            default :
                $this->addFlash('error', 'Le tableau a trop de colonne');
                return $this->redirectToRoute('import_csv');

        }

        switch ($csv->getType()->getName()){
            case ActivityType::QCM_ACTIVITY :
                $form = $this->createForm(CSVselectType::class, $csv);
                break;
            case ActivityType::ASSOCIATION_ACTIVITY :
                $form = $this->createForm(CSVselectAssociationType::class, $csv);
                break;
            default :
                $this->addFlash('error', 'Ce type d\'activité n\'est pas encore implémenté');
                return $this->redirectToRoute('import_csv');
        }

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($csv);
            $manager->flush();

            return $this->redirectToRoute('csv_final', ['id' => $id]);
        }

        return $this->render('csv/step2.html.twig', [
            'form_csv' => $form->createView(),
            'current_menu' => 'csv'
        ]);
    }

    /**
     * Route permettant la création des activités selon le type correspondant au début de l'import
     *
     * @Route("/import/csv/{id}/final", name="csv_final")
     * @throws \League\Csv\Exception
     */
    public function csvCreationActivity($id = null, CSV $csv, ObjectManager $manager, ActivityRepository $repository, Request $request, ActivityTypeRepository $activityTypeRepository){

        $projectDir = $this->getParameter('kernel.project_dir');
        $csvPath = $projectDir . "\public\csv\\" . $csv->getFile();
        $csvReturn = Reader::createFromPath($csvPath, 'r');
        $csvReturn->setHeaderOffset(0);
        $csvReturn->setDelimiter(';');
        $records = $csvReturn->getRecords();
        switch ($csv->getType()->getName()){
            case ActivityType::QCM_ACTIVITY :
                foreach($records as $offset => $record){

                    $activity = $repository->findOneBy(['name' => $record[$csv->getColumn1()] ]);

                    if(!$activity){
                        $activity = new Activity();
                        $activity->setName($record[$csv->getColumn1()]);
                        $type = $activityTypeRepository->findOneBy(['name' => ActivityType::QCM_ACTIVITY]);
                        $activity->setType($type);
                    }
                    $activity->setCreatedBy($this->getUser());
                    $activity->setVisible(true);

                    $question = new Questions();
                    if($csv->getColumn2()){
                        $question->setQuestion($record[$csv->getColumn2()]);
                    }
                    if($csv->getColumn3()){
                        $question->setBonneReponse1($record[$csv->getColumn3()]);
                    }
                    if($csv->getColumn4()){
                        $question->setBonneReponse2($record[$csv->getColumn4()]);
                    }
                    if($csv->getColumn5()){
                        $question->setMauvaiseReponse1($record[$csv->getColumn5()]);
                    }
                    if($csv->getColumn6()){
                        $question->setMauvaiseReponse2($record[$csv->getColumn6()]);
                    }
                    $question->setPoints($record[$csv->getColumn7()]);

                    $manager->persist($question);

                    $activity->addQuestion($question);

                    $manager->persist($activity);

                    $manager->flush();
                }
                break;
            case ActivityType::ASSOCIATION_ACTIVITY :
                foreach ($records as $offset => $record) {
                    $activity = $repository->findOneBy(['name' => $record[$csv->getColumn1()] ]);
                    if(!$activity){
                        $activity = new Activity();
                        $activity->setName($record[$csv->getColumn1()]);
                        $type = $activityTypeRepository->findOneBy(['name' => ActivityType::ASSOCIATION_ACTIVITY]);
                        $activity->setType($type);
                    }
                    $activity->setCreatedBy($this->getUser());
                    $activity->setVisible(true);

                    $groupe = new QuestionsGroupes();

                    if($csv->getColumn2()){
                        $groupe->setName($record[$csv->getColumn2()]);
                    }

                    $element = new QuestionsReponses();
                    if($csv->getColumn3()){
                        $element->setName($record[$csv->getColumn3()]);
                        $manager->persist($element);
                        $groupe->addQuestionsReponse($element);
                    }
                    $element = new QuestionsReponses();
                    if($csv->getColumn4()){
                        $element->setName($record[$csv->getColumn4()]);
                        $manager->persist($element);
                        $groupe->addQuestionsReponse($element);
                    }
                    $element = new QuestionsReponses();
                    if($csv->getColumn5()){
                        $element->setName($record[$csv->getColumn5()]);
                        $manager->persist($element);
                        $groupe->addQuestionsReponse($element);
                    }
                    $element = new QuestionsReponses();
                    if($csv->getColumn6()){
                        $element->setName($record[$csv->getColumn6()]);
                        $manager->persist($element);
                        $groupe->addQuestionsReponse($element);
                    }
                    $element = new QuestionsReponses();
                    if($csv->getColumn7()){
                        $element->setName($record[$csv->getColumn7()]);
                        $manager->persist($element);
                        $groupe->addQuestionsReponse($element);
                    }
                    $manager->persist($groupe);

                    $activity->addQuestionsGroupe($groupe);

                    $manager->persist($activity);

                    $manager->flush();

                }
                break;
            default :
                $this->addFlash('error', 'Ce type d\'activité n\'est pas encore implémenté');
                return $this->redirectToRoute('import_csv');
        }

        return $this->redirectToRoute('activity');
    }

    /**
     * Route permettant l'export des activités du type QCM pour le moment
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/export/csv", name="export_csv")
     * @throws \TypeError
     * @throws \League\Csv\Exception
     */
    public function exportActivityCsv(Request $request, ActivityRepository $activityRepository){

        $csv = new CSV();
        $form = $this->createForm(CSVExportType::class, $csv);
        $form->handleRequest($request);

        $tableauFinal[] = ['Activite', 'Question', 'Bonne Reponse 1', 'Bonne reponse 2', 'Bonne reponse 3',
                        'Mauvaise reponse 1', 'Mauvaise reponse 2', 'Mauvaise reponse 3', 'Points'];

        $activities = $activityRepository->findAll();
        foreach ($activities as $activity ){
            $questions = $activity->getQuestion();
            foreach ($questions as $question){
                $tableauFinal[] = [$question->getActivity()->getName(), $question->getQuestion(),
                                    $question->getBonneReponse1(), $question->getBonneReponse2(),
                                    $question->getBonneReponse3(), $question->getMauvaiseReponse1(),
                                    $question->getMauvaiseReponse2(), $question->getMauvaiseReponse3(),
                                    $question->getPoints()
                    ];
            }
        }

        if($form->isSubmitted() && $form->isValid()){

            $name = $csv->getName();

            $projectDir = $this->getParameter('kernel.project_dir');
            $csvPath = $projectDir . "\public\csv\\" . $name .'.csv';
            $writer = Writer::createFromPath($csvPath, 'w+');
            $writer->setDelimiter(';');
            $writer->insertAll($tableauFinal);

            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="'.$name.'csv"');
            $reader = Reader::createFromPath($csvPath, 'r');
            $reader->output($name.'.csv');
            die();
        }

        return $this->render('csv/export.html.twig', [
            'form_csv' => $form->createView(),
            'current_menu' => 'csv'
        ]);
    }
}
