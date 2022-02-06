<?php
	// Add JavaScripts
    $this->uc_CompilatorData->addJavaScript('uc_resources/distribution/fontawesome/js/all.min.js');
	$this->uc_CompilatorData->addJavaScript('uc_resources/distribution/jquery/jquery.min.js', 'bottom');
	$this->uc_CompilatorData->addJavaScript('uc_resources/distribution/bootstrap/js/bootstrap.min.js', 'bottom');
    // $this->uc_CompilatorData->addJavaScript('uc_resources/distribution/bootstrap/js/bootstrap.bundle.min.js', 'bottom');
	$this->uc_CompilatorData->addJavaScript('uc_templates/uCrewBase/js/scripts.js', 'bottom');
	// Add styles
    $this->uc_CompilatorData->addCSS('uc_resources/distribution/bootstrap/css/bootstrap.min.css');
    $this->uc_CompilatorData->addCSS('uc_templates/uCrewBase/css/styles.css');
	// Init header of template
	$this->uc_CompilatorData->template_header = '
<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="uCrew" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
';
	// Set body start code
	$this->uc_CompilatorData->template_body = "\t</head>\n\t<body class=\"sb-nav-fixed\">\n";
	// Set footer
	$this->uc_CompilatorData->template_footer = '
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">&copy; uCrew ' . $this->version . '</div>
                            <div>
                                <a href="#">Инструкция</a>
                                &middot;
                                <a target="_blank" rel="noopener noreferrer" href="https://github.com/stellar-creator/uCrew">Github</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>' . "\n";

    // Set page eng
    $this->uc_CompilatorData->page_end = "\n\t</body>\n</html>\n";

    // Set topbar header
    $this->uc_CompilatorData->topbar["header"] = '
 		<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="/"> '.$this->system["organization"].' </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form method="get" action="/" class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input type="hidden" name="handler" value="search"> 
                    <input name="data" class="form-control" type="text" placeholder="Поиск по системе" aria-label="Поиск по системе" aria-describedby="btnNavbarSearch" required="required" />
                    <input type="submit" class="btn btn-primary" id="btnNavbarSearch" value="Поиск" />
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
    ' . "\n";
    // Set topbar footer
    $this->uc_CompilatorData->topbar["footer"] = '
                   </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">'  . "\n";
    // Set topbar item template 
    $this->uc_CompilatorData->topbar["item"] = "\t\t\t\t\t\t" . '<li><a class="dropdown-item" href="%page%">%name%</a></li>' ."\n";
    // Set topbar item template 
    $this->uc_CompilatorData->topbar["divider"] = "\t\t\t\t\t\t" . '<li><hr class="dropdown-divider" /></li>' ."\n";
    // Set sidebar setup
    $this->uc_CompilatorData->sidebar["header"] = '
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Основное меню</div>
                            <a class="nav-link" href="/">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Главная страница
                            </a>
';
    // Set sidebar footer
    $this->uc_CompilatorData->sidebar["footer"] = '
                         </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Пользователь:</div>
                        ' . $_SESSION['user_name'] . '
                    </div>
                </nav>
            </div>
';
    // Set sidebar divider
    $this->uc_CompilatorData->sidebar["divider"] = "\t\t\t\t\t\t\t" . '<div class="sb-sidenav-menu-heading">%section%</div>';
    // Set sidebar once item
    $this->uc_CompilatorData->sidebar["once_item"] = '
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                %item%
                            </a>
';
    // Set multiple items section header
    $this->uc_CompilatorData->sidebar["subitems_header"] = '
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#%subitems_title_tag%" aria-expanded="false" aria-controls="%subitems_title_tag%">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                %subitems_title%
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="%subitems_title_tag%" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
';    

    // Set multiple items section header
    $this->uc_CompilatorData->sidebar["subitem"] = '
                                <a class="nav-link" href="/?page=%page%">%subitem%</a>
';    
    // Set multiple items section header
    $this->uc_CompilatorData->sidebar["subitems_footer"] = '
                                </nav>
                            </div>
';
    // Set page header
    $this->uc_CompilatorData->page["header"] = '
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">%page%</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">%page_pipe%</li>
                        </ol>
';   
    // Set page header
    $this->uc_CompilatorData->page["footer"] = '
                    </div>
                </main>
';
?>