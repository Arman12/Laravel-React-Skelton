<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{url('/dashboard')}}">
          <span class="menu-title">Dashboard</span>
          <i class="mdi mdi-home menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('upload.csv.index')}}">
          <span class="menu-title">Upload Csv</span>
          <i class="mdi mdi-upload menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('email.index')}}">
          <span class="menu-title">Email Templates</span>
          <i class="mdi mdi-email menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('sms.index')}}">
          <span class="menu-title">Sms Templates</span>
          <i class="mdi mdi-message-image menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('campaign.index')}}">
          <span class="menu-title">Campaigns</span>
          <i class="mdi mdi-shuffle-variant menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{url('/regenerate-docs')}}">
          <span class="menu-title">Regenerate Docs</span>
          <i class="mdi mdi mdi-file-document menu-icon"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('logout')}}">
          <span class="menu-title">Logout</span>
          <i class="mdi mdi-logout menu-icon"></i>
        </a>
      </li>

    </ul>
  </nav>