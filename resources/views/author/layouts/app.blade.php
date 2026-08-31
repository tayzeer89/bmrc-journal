<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
    @yield('title','Author Dashboard') | BMRC Journal
</title>


<link rel="icon"
      type="image/png"
      href="{{ asset('favicon.png') }}">


<link rel="apple-touch-icon"
      href="{{ asset('favicon.png') }}">



{{-- Bootstrap 5 --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">



{{-- Bootstrap Icons --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet">



<style>


body {

    background:#f5f7fb;

    font-family:Arial, Helvetica, sans-serif;

}



/* =========================
   TOP NAVBAR
========================= */


.top-navbar {

    height:64px;

    background:#ffffff;

    border-bottom:1px solid #e5e7eb;

    position:fixed;

    top:0;

    left:0;

    right:0;

    z-index:1050;

}



.brand-area {


    width:250px;

    height:64px;

    display:flex;

    align-items:center;

    padding:0 20px;

    border-right:1px solid #e5e7eb;


}



.brand-area img {

    height:42px;

    width:42px;

    object-fit:contain;

}



.brand-text {

    margin-left:10px;

    line-height:1.1;

}



.brand-title {

    font-size:16px;

    font-weight:700;

    color:#1f2937;

}



.brand-subtitle {

    font-size:11px;

    color:#6b7280;

}





/* =========================
   SIDEBAR
========================= */


.sidebar {


    position:fixed;


    top:64px;


    left:0;


    bottom:0;


    width:250px;


    background:#ffffff;


    border-right:1px solid #e5e7eb;


    overflow-y:auto;


    padding:15px 10px;


    z-index:1045;


}




.sidebar-section {


    font-size:11px;


    font-weight:700;


    color:#9ca3af;


    text-transform:uppercase;


    padding:15px 12px 7px;


}




.sidebar .nav-link {


    display:flex;


    align-items:center;


    gap:10px;


    color:#4b5563;


    padding:10px 12px;


    margin-bottom:3px;


    border-radius:8px;


    font-size:14px;


    transition:.2s;


}



.sidebar .nav-link i {


    width:20px;


    font-size:17px;


}



.sidebar .nav-link:hover {


    background:#f3f4f6;


    color:#0d6efd;


}




.sidebar .nav-link.active {


    background:#eaf2ff;


    color:#0d6efd;


    font-weight:600;


}




.sidebar .arrow {


    margin-left:auto;


    font-size:12px;


}




.submenu {


    padding-left:18px;


}



.submenu .nav-link {


    font-size:13px;


    padding:8px 12px;


}





/* =========================
   MAIN CONTENT
========================= */


.main-wrapper {


    margin-left:250px;


    padding-top:64px;


    min-height:100vh;


}




.main-content {


    padding:25px;


}





/* =========================
   USER
========================= */


.user-avatar {


    height:38px;


    width:38px;


    border-radius:50%;


    background:#0d6efd;


    color:white;


    display:flex;


    justify-content:center;


    align-items:center;


    font-weight:600;


}





/* =========================
   OVERLAY
========================= */


.sidebar-overlay {


    display:none;


}





/* =========================
   MOBILE
========================= */


@media(max-width:991.98px){



.brand-area {


    width:auto;

    border-right:0;

}



.sidebar {


    left:-260px;

    transition:.3s ease;


}



.sidebar.show {


    left:0;


}



.main-wrapper {


    margin-left:0;


}



.sidebar-overlay {


    position:fixed;


    top:64px;


    left:0;


    right:0;


    bottom:0;


    background:rgba(0,0,0,.35);


    z-index:1040;


}



.sidebar-overlay.show {


    display:block;


}



}





/* =========================
   FOOTER
========================= */


.footer {


    padding:20px 25px;


    color:#6b7280;


    font-size:13px;


}



/* FIX SUBMISSION COLLAPSE MENU */

#submissionMenu {

    overflow: visible !important;

}


#submissionMenu.show {

    display:block !important;

}



.submenu .nav-link {

    display:flex !important;

    width:100%;

    color:#4b5563;

}



.collapse:not(.show){

    display:none;

}



.sidebar {

    overflow-y:auto;

}



.sidebar .collapse {

    visibility:visible !important;

}



</style>



@vite(['resources/css/app.css','resources/js/app.js'])


@stack('styles')


</head>



<body>



{{-- TOP NAVBAR --}}


<nav class="top-navbar">


<div class="d-flex justify-content-between align-items-center h-100">



<div class="brand-area">


<button

class="btn btn-light d-lg-none me-2"

id="sidebarToggle">

<i class="bi bi-list fs-5"></i>

</button>



<img src="{{ asset('favicon.png') }}"
     alt="BMRC Logo">



<div class="brand-text">


<div class="brand-title">

BMRC Journal

</div>


<div class="brand-subtitle">

Author Portal

</div>


</div>


</div>





<div class="d-flex align-items-center px-3 gap-3">



<div class="dropdown">


<button

class="btn d-flex align-items-center gap-2"

data-bs-toggle="dropdown">


<div class="user-avatar">

{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}

</div>



<div class="d-none d-md-block">


<div class="fw-semibold small">

{{ auth()->user()->name }}

</div>


<div class="text-muted small">

Author

</div>


</div>


<i class="bi bi-chevron-down"></i>


</button>


<ul class="dropdown-menu dropdown-menu-end">


<li>

<a class="dropdown-item"
href="{{ route('author.profile.edit') }}">

<i class="bi bi-person me-2"></i>

Profile

</a>

</li>


<li>

<form method="POST"
action="{{ route('author.logout') }}">

@csrf

<button class="dropdown-item text-danger">

<i class="bi bi-box-arrow-right me-2"></i>

Logout

</button>


</form>

</li>


</ul>


</div>



</div>



</div>


</nav>





<div class="sidebar-overlay"
id="sidebarOverlay"></div>





<aside class="sidebar"
id="sidebar">


{{-- Sidebar continues in Part 2 --}}

{{-- =========================
     SIDEBAR MENU
========================= --}}


<div class="sidebar-section">
    Main
</div>


<a href="{{ route('author.dashboard') }}"
class="nav-link {{ request()->routeIs('author.dashboard') ? 'active' : '' }}">

<i class="bi bi-speedometer2"></i>

Dashboard

</a>




<div class="sidebar-section">
    Author
</div>


<a href="{{ route('author.profile.edit') }}"
class="nav-link">

<i class="bi bi-person"></i>

My Profile

</a>



<a href="{{ route('author.profile.step2') }}"
class="nav-link">

<i class="bi bi-person-vcard"></i>

Personal Information

</a>



<a href="{{ route('author.profile.step3') }}"
class="nav-link">

<i class="bi bi-building"></i>

Professional Information

</a>






<div class="sidebar-section">
    Manuscripts
</div>




<button

type="button"

class="nav-link border-0 bg-transparent w-100 text-start"

data-bs-toggle="collapse"

data-bs-target="#submissionMenu"

aria-controls="submissionMenu">


<i class="bi bi-journal-text"></i>


<span>

Submissions

</span>


<i class="bi bi-chevron-down arrow"></i>


</button>




<div class="collapse submenu"
id="submissionMenu">


<a href="{{ route('author.submission.create') }}"
class="nav-link">

<i class="bi bi-plus-circle"></i>

New Submission

</a>



<a href="{{ route('author.manuscripts.index') }}"
class="nav-link">

<i class="bi bi-files"></i>

My Manuscripts

</a>



<a href="{{ route('author.drafts.index') }}"
class="nav-link">

<i class="bi bi-file-earmark"></i>

Drafts

</a>



<a href="{{ route('author.submitted.index') }}"
class="nav-link">

<i class="bi bi-send"></i>

Submitted

</a>



<a href="#"
class="nav-link">

<i class="bi bi-search"></i>

Under Review

</a>



<a href="#"
class="nav-link">

<i class="bi bi-arrow-repeat"></i>

Revision Required

</a>



<a href="#"
class="nav-link">

<i class="bi bi-check-circle"></i>

Accepted Articles

</a>



<a href="#"
class="nav-link">

<i class="bi bi-book"></i>

Published Articles

</a>


</div>





<div class="sidebar-section">
    Finance
</div>


<a href="{{route('author.payments.index')}}"
class="nav-link">

<i class="bi bi-credit-card"></i>

Payments

</a>



<a href="#"
class="nav-link">

<i class="bi bi-receipt"></i>

Invoices

</a>





<div class="sidebar-section">
    Publication
</div>


<a href="#"
class="nav-link">

<i class="bi bi-file-earmark-pdf"></i>

Proofs

</a>



<a href="#"
class="nav-link">

<i class="bi bi-book-half"></i>

Published Articles

</a>






<div class="sidebar-section">
    Communication
</div>



<a href="#"
class="nav-link">

<i class="bi bi-bell"></i>

Notifications

</a>



<a href="#"
class="nav-link">

<i class="bi bi-chat-dots"></i>

Messages

</a>






<div class="sidebar-section">
    Resources
</div>



<a href="#"
class="nav-link">

<i class="bi bi-folder"></i>

Documents

</a>



<a href="#"
class="nav-link">

<i class="bi bi-journal-bookmark"></i>

Submission Guidelines

</a>



<a href="#"
class="nav-link">

<i class="bi bi-question-circle"></i>

Help / Support

</a>







<div class="sidebar-section">
    Account
</div>



<a href="#"
class="nav-link">

<i class="bi bi-key"></i>

Change Password

</a>




<form method="POST"
action="{{ route('author.logout') }}">

@csrf


<button type="submit"
class="nav-link border-0 bg-transparent w-100 text-start">


<i class="bi bi-box-arrow-right"></i>


Logout


</button>


</form>



</aside>





{{-- =========================
     MAIN CONTENT
========================= --}}


<div class="main-wrapper">



<main class="main-content">


@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle me-2"></i>

{{ session('success') }}


<button class="btn-close"
data-bs-dismiss="alert"></button>


</div>

@endif




@if(session('error'))

<div class="alert alert-danger alert-dismissible fade show">


<i class="bi bi-exclamation-circle me-2"></i>


{{ session('error') }}


<button class="btn-close"
data-bs-dismiss="alert"></button>


</div>

@endif





@yield('content')



</main>





<footer class="footer">


<div class="d-flex justify-content-between flex-wrap">


<div>

© {{ date('Y') }}

Bangladesh Medical Research Council (BMRC)

</div>



<div>

BMRC Journal Author Portal

</div>


</div>


</footer>




</div>






<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>





<script>


document.addEventListener('DOMContentLoaded',function(){



// MOBILE SIDEBAR


const sidebar=document.getElementById('sidebar');

const toggle=document.getElementById('sidebarToggle');

const overlay=document.getElementById('sidebarOverlay');



if(toggle){


toggle.addEventListener('click',function(){


sidebar.classList.toggle('show');

overlay.classList.toggle('show');


});


}



if(overlay){


overlay.addEventListener('click',function(){


sidebar.classList.remove('show');

overlay.classList.remove('show');


});


}





// KEEP SUBMISSION MENU OPEN


const submissionMenu =
document.getElementById('submissionMenu');



if(submissionMenu){


const collapse =
new bootstrap.Collapse(
submissionMenu,
{
toggle:false
}
);



if(localStorage.getItem('submissionMenu')==='open'){


collapse.show();


}



submissionMenu.addEventListener(
'shown.bs.collapse',
function(){

localStorage.setItem(
'submissionMenu',
'open'
);

}
);



submissionMenu.addEventListener(
'hidden.bs.collapse',
function(){

localStorage.setItem(
'submissionMenu',
'close'
);

}
);



}



});



</script>



@stack('scripts')


</body>

</html>