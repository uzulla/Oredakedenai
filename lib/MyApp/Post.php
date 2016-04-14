<?php
namespace MyApp;

class Post
{
    public $c;
    public function __construct($c)
    {
        $this->c = $c;
    }

    public function showList($params)
    {
        $m_post = new \MyApp\Db\Post();
        $list = $m_post->getAll();
        echo $this->c['template']->render("list.twig", ['list'=>$list]);
    }

    public function create($params)
    {
        $name = (string)$_POST['name'] ?? null;
        $text = (string)$_POST['text'] ?? null;

        $m_post = new \MyApp\Db\Post();
        $m_post->insert($name, $text);

        header('Location: /');
    }

    public function show($params)
    {
        $id = $params['id'];

        $m_post = new \MyApp\Db\Post();
        $row = $m_post->get($id);
        if($row===false){
            http_response_code(404);
            echo "NotFound";
            exit;
        }

        echo $this->c['template']->render("show.twig", ['row'=>$row]);
    }

    public function reset($params)
    {
        $m_post = new \MyApp\Db\Post();
        $m_post->reset();

        echo "DBをリセットしました";
    }

    public function notfound()
    {
        http_response_code(404);
        echo "NotFound";
    }

}