<?php
require_once 'Http/Controllers/sections/Section.php';


// sections
uri('sections', 'App\Section', 'index');
uri('section/store', 'App\Section', 'sectionStore', 'POST');