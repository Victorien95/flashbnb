<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;


class Paginator
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;
    private $query;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath)
    {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
        $this->query = null;
    }

    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page'=> $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getData()
    {
        if (!$this->entityClass){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle le service de pagination doit travailler !
            Utilisez la méthode setEntityClass() de votre objet Paginator");
        }
        $offset = $this->currentPage * $this->limit - $this->limit;

        $repo = $this->manager->getRepository($this->entityClass);

        if ($this->query === null){
            $data = $repo->findBy([], [], $this->limit,  $offset);
        }else{
            $data = $this->query;
        }

        return $data;

    }

    public function getPages()
    {
        if (!$this->entityClass){
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle le service de pagination doit travailler !
            Utilisez la méthode setEntityClass() de votre objet Paginator");
        }

        $repo = $this->manager->getRepository($this->entityClass);

        if ($this->query === null){
            $total = count($repo->findAll());
        }else{
            $total = count($this->query);
        }
        $pages = ceil($total / $this->getLimit());

        return $pages;

    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     * @return Paginator
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return Paginator
     */
    public function setLimit(int $limit): Paginator
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return Paginator
     */
    public function setCurrentPage(int $currentPage): Paginator
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getRoute(): ?mixed
    {
        return $this->route;
    }

    /**
     * @param mixed|null $route
     * @return Paginator
     */
    public function setRoute(?mixed $route): Paginator
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param mixed $templatePath
     * @return Paginator
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    /**
     * @return null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param null $query
     * @return Paginator
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

}