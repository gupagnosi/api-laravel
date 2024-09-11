<?php

namespace App\Http\Controllers\Contracts;

interface ProductControllerInterface
{
    public function index();

    public function show(int $id);

    public function store(\Illuminate\Http\Request $request);

    public function update(\Illuminate\Http\Request $request, int $id);

    public function destroy(int $id);
}