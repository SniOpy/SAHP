<?php

require_once __DIR__ . '/../Core/Controller.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'title' => 'SAHP – Assainissement & Curage'
        ]);
    }
}