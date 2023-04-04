<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
#import route
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;

class IndexController extends AbstractController
{ 
    public function home(EntityManagerInterface $en)
    {
        $articles = $en->getRepository(Article::class)->findAll();
        //$articles=['arr','aa']  ;
        return $this->render('index.html.twig',['articles'=>$articles]);
    }
    public function save(ManagerRegistry $doctrine) {
        $entityManager = $doctrine->getManager();
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(1000);
        
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('Article enregisté avec id '.$article->getId());
        }
        public function new(Request $request,EntityManagerInterface $en) {
            $article = new Article();
            $form = $this->createFormBuilder($article)
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)->getForm();
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            
        
            $en->persist($article);
            $en->flush();
            
            return $this->redirectToRoute('home');
            }
            return $this->render('new.html.twig',['form' => $form->createView()]);}

            public function edit(Request $request, $id,EntityManagerInterface $en) {
                $article = new Article();
               $article = $en->getRepository(Article::class)->find($id);
                
               $form = $this->createFormBuilder($article)
               ->add('nom', TextType::class)
               ->add('prix', TextType::class)
               ->add('save', SubmitType::class, array('label' => 'Modifier'))->getForm();
              
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid()) {
                
                
                   
                $en->flush();
                
                return $this->redirectToRoute('home');
                }
                
                return $this->render('edit.html.twig', ['form' =>
               $form->createView()]);
                }
         public function show($id,EntityManagerInterface $en) {
                    $article = $en->getRepository(Article::class) ->find($id);
                    return $this->render('show.html.twig',
                    array('article' => $article));
                     }
                     public function delete(Request $request, $id,EntityManagerInterface $en) {
                        $article = $en->getRepository(Article::class)->find($id);
                        
                     
                        $en->remove($article);
                        $en->flush();
                        
                        $response = new Response();
                        $response->send();
                        return $this->redirectToRoute('home');}
                       
            
    }
   
   

