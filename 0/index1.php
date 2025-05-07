<?php
require_once 'config-path.php';
require_once '../session/session-manager.php';
SessionManager::checkSession();
$sessionVars = SessionManager::SessionVariables();

$mobile_no = $sessionVars['mobile_no'];
$user_id = $sessionVars['user_id'];
$role = $sessionVars['role'];
$user_login_id = $sessionVars['user_login_id'];
$user_name = $sessionVars['user_name'];
$user_email = $sessionVars['user_email'];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* body {
      font-family: 'Inter', sans-serif;
      background-color: #f8fafc;
      color: #1e293b;
      line-height: 1.5;
      min-height: 100vh;
      padding: 1.5rem;
    } */

    .container {
      /* max-width: 1400px; */
      margin: 0 auto;
      /* border: 2px solid #000; */
      /* padding: 20px; */
    }

    .section-title {
      /* font-size: 1.5rem;
      font-weight: 600; */
      /* margin-bottom: 1.5rem; */
      padding-left: 1rem;
      border-left: 4px solid #6366f1;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .section-title.purple {
      border-color: #8b5cf6;
    }

    .section-title.blue {
      border-color: #3b82f6;
    }

    .section-title.amber {
      border-color: #f59e0b;
    }

    .section {
      margin-bottom: 0.5rem;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }

    /* Optional: prevent responsiveness to maintain 3x3 layout even on mobile */
    /* @media (max-width: 768px) {
      .grid {
        grid-template-columns: repeat(3, 1fr);
        overflow-x: auto;
      }
    } */
    @media (max-width: 767px) {
      .grid {
        grid-template-columns: 1fr;
        /* stack cards vertically */
        gap: 1rem;
        /* optional: adjust spacing between cards */
      }
    }

    .card {
      background: #ffffff;
      border-radius: 0.5rem;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.6s ease forwards;
      opacity: 0;
      transform: translateY(20px);
      /* border: 2px solid #000; */
    }

    /* .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    } */

    .card-header {
      padding: 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .card-header h3 {
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .card-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
      transform: rotate(45deg);
      pointer-events: none;
    }

    .card-body {
      padding: 1rem;
    }

    .purple-gradient {
      background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    }

    .blue-gradient {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .cyan-gradient {
      background: linear-gradient(135deg, #06b6d4, #14b8a6);
    }

    .amber-gradient {
      background: linear-gradient(135deg, #f97316, #f59e0b);
    }

    .purple-light-bg {
      background: linear-gradient(to bottom right, #f5f3ff, #ede9fe);
    }

    .blue-light-bg {
      background: linear-gradient(to bottom right, #e0f2fe, #bae6fd);
    }

    .cyan-light-bg {
      background: linear-gradient(to bottom right, #cffafe, #a5f3fc);
    }

    .amber-light-bg {
      background: linear-gradient(to bottom right, #fef3c7, #fde68a);
    }

    .data-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 0;
      border-bottom: 1px solid #e2e8f0;
    }

    .data-row:last-child {
      border-bottom: none;
    }

    .data-label {
      /* color: #64748b; */
      font-weight: 500;
    }

    .data-value {
      /* color: #64748b; */
      font-weight: 600;
    }

    /* .badge {
      padding: 0.35rem 0.75rem;
      border-radius: 2rem;
      font-size: 0.875rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 60px;
    } */

    .badge-success {
      /* background-color: #10b981; */
      background-color: green;
      color: white;
      box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
      animation: pulse 2s infinite;
      padding: 8px;
      border-radius: 10px;
      font-weight: bold;
    }

    .badge-danger {
      background-color: #ef4444;
      color: white;
      padding: 8px;
      border-radius: 10px;
      font-weight: bold;
    }

    .badge-outline-success {
      background-color: rgba(16, 185, 129, 0.1);
      /* color: #10b981; */
      color: green;
      border: 1px solid rgba(16, 185, 129, 0.2);
      padding: 5px;
      border-radius: 10px;
      font-weight: bold;
    }

    .badge-outline-danger {
      background-color: rgba(239, 68, 68, 0.1);
      /* color: #ef4444; */
      color: red;
      border: 1px solid rgba(239, 68, 68, 0.2);
      padding: 5px;
      border-radius: 10px;
      font-weight: bold;
    }

    .flow-meter {
      margin-top: 0.5rem;
      height: 0.5rem;
      background-color: #e2e8f0;
      border-radius: 0.25rem;
      overflow: hidden;
      position: relative;
    }

    .flow-meter-bar-on {
      position: absolute;
      height: 100%;
      width: 0;
      background: linear-gradient(90deg, #818cf8, #6366f1);
      border-radius: 0.25rem;
      animation: underlineAnimation 5s linear infinite;
    }

    .flow-meter-bar-off {
      position: absolute;
      height: 100%;
      width: 0;
      background: linear-gradient(90deg, #818cf8, #6366f1);
      border-radius: 0.25rem;
      /* animation: underlineAnimation 5s linear infinite; */
    }

    @keyframes underlineAnimation {
      0% {
        width: 0;
        left: 0;
      }

      40% {
        width: 100%;
        left: 0;
      }

      60% {
        width: 100%;
        left: 0;
      }

      100% {
        width: 0;
        left: 100%;
      }
    }

    .center-content {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100%;
    }

    .operation-mode {
      font-size: 1.5rem;
      font-weight: 600;
      text-align: center;
      color: #6d28d9;
    }

    .operation-mode-subtitle {
      font-size: 0.875rem;
      color: #7c3aed;
      margin-top: 0.5rem;
    }

    .pulse-dot {
      display: inline-block;
      width: 8px;
      height: 8px;
      background-color: currentColor;
      border-radius: 50%;
      margin-left: 0.5rem;
      animation: pulse 2s infinite;
      vertical-align: middle;
      position: relative;
    }

    @keyframes pulse {
      0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
      }

      70% {
        transform: scale(1);
        box-shadow: 0 0 0 5px rgba(255, 255, 255, 0);
      }

      100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
      }
    }

    /* Add these additional styles to ensure visibility in different contexts */
    .badge-success .pulse-dot {
      background-color: #ffffff;
    }

    .badge-outline-success .pulse-dot {
      background-color: #28a745;
    }

    .operation-mode .pulse-dot {
      background-color: #7b68ee;
    }

    .tabs {
      margin-bottom: 1rem;
    }

    .tabs-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .tab-trigger {
      padding: 0.5rem;
      /* background-color:  #f8fafc; */
      border: 1px solid #e2e8f0;
      border-radius: 0.25rem;
      font-weight: 500;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    /* .tab-trigger:hover {
      background-color: #f1f5f9;
    } */

    .tab-trigger.active {
      background-color: #f59e0b;
      color: white;
      border-color: #f59e0b;
    }

    /* .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.3s ease;
    } */

    /* .grid-2-cols {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
    }

    @media (max-width: 768px) {
      .grid-2-cols {
        grid-template-columns: 1fr;
      }
    } */

    .small-card {
      /* background-color:  #f8fafc; */
      border-radius: 0.25rem;
      padding: 0.75rem;
      text-align: center;
    }

    .small-card-label {
      font-size: 1rem;
      /* color: #64748b; */
      margin-bottom: 0.25rem;
    }

    .battery-icon {
      font-size: 1.3rem;
    }

    /* .small-card-value {
      font-weight: 600;
    } */

    .grid-3-cols {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }

    .grid-2-cols-equal {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }

    /* .section-subtitle {
      font-size: 1.125rem;
      font-weight: 500;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    } */

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        opacity: 1;
      }

      70% {
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        opacity: 0.7;
      }

      100% {
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        opacity: 1;
      }
    }

    .bg-grid-pattern {
      background-image: linear-gradient(to right, rgba(99, 102, 241, 0.1) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
      background-size: 20px 20px;
    }

    /* Animation delays for staggered appearance */
    .grid .card:nth-child(1) {
      animation-delay: 0.1s;
    }

    .grid .card:nth-child(2) {
      animation-delay: 0.2s;
    }

    .grid .card:nth-child(3) {
      animation-delay: 0.3s;
    }

    .grid .card:nth-child(4) {
      animation-delay: 0.4s;
    }

    .grid .card:nth-child(5) {
      animation-delay: 0.5s;
    }

    .grid .card:nth-child(6) {
      animation-delay: 0.6s;
    }

    .hidden {
      display: none;
    }

    /* New styles for the table-like layout */
    .dashboard-container {
      max-width: 1200px;
      margin: 0px auto;
      /* background-color: white; */
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      border-radius: 12px;
      overflow: hidden;
    }

    .dashboard-header {
      background: linear-gradient(to right, #6366f1, #818cf8);
      color: white;
      padding: 1.5rem 2rem;
    }

    .dashboard-title {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 4px;
    }

    .dashboard-subtitle {
      font-size: 14px;
      opacity: 0.9;
    }

    .dashboard-content {
      /* max-width:1200px; */
      padding: 1.5rem 2rem;
    }

    .details-row {
      display: flex;
      gap: 30px;
      margin-bottom: 30px;
    }

    @media (max-width: 768px) {
      .details-row {
        flex-direction: column;
      }
    }

    .details-column {
      flex: 1;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      transition: transform 0.2s, box-shadow 0.2s;
      display: flex;
      flex-direction: column;
    }



    .table-container {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .table-like {
      width: 100%;
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .table-content {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .table-row {
      display: flex;
      border-bottom: 1px solid;
      transition: background-color 0.2s;
    }


    .table-row:last-child {
      border-bottom: none;
    }

    .table-header {
      font-weight: 600;
      background-color: #f1f5f9;
      color: var(--slate-700);
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .table-cell {
      padding: 12px 15px;
      flex: 1;
      display: flex;
      align-items: center;
    }

    /* .grid-layout {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 15px;
      padding: 20px;
      flex: 1;
    } */

    .grid-layout {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
      gap: 15px;
      padding: 20px;
      flex: 1;
    }

    /* Force 4 columns in grid for wider screens */
    @media (min-width: 768px) {
      .grid-layout {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .grid-item {
      /* background-color: #f8fafc; */
      border-radius: 8px;
      padding: 15px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      transition: all 0.2s ease;
      border: 1px solid none;
    }

    /* .grid-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      border-color:  #818cf8;
    } */

    .item-title {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
      font-weight: 500;
      /* color: var(--slate-700); */
    }

    .item-status {
      margin-bottom: 6px;
    }

    .item-details {
      font-size: 8px;
      /* color: #475569; */
    }

    @media (min-width:1500px) {
      .flow-value {
        font-size: 10px !important;
        font-weight: 600;
        color: var(--slate-700);
        margin-bottom: 2px;
      }

      .item-details {
        font-size: 12px;
        /* color: #475569; */
      }

    }

    /* .badge-success {
      background-color: rgba(16, 185, 129, 0.15);
      color: #10b981;
      color:green;
    } */
    .badge-success {
      background-color: rgba(16, 185, 129, 0.15);
      color: green;
      display: inline-flex;
      align-items: center;
      padding: 8px;
      border-radius: 8px;
      font-size: 16px;
      /* Adjust as needed */
      line-height: 1.2;
    }

    .badge-success i {
      margin-right: 4px;
      font-size: 18px;
      /* Match with text if needed */
      vertical-align: middle;
    }

    .badge-danger {
      background-color: rgba(239, 68, 68, 0.15);
      color: red;
      display: inline-flex;
      align-items: center;
      padding: 8px;
      border-radius: 8px;
      font-size: 16px;
      /* Adjust as needed */
      line-height: 1.2;
    }

    .badge-danger i {
      margin-right: 4px;
      font-size: 18px;
      /* Match with text if needed */
      vertical-align: middle;
    }

    /* .badge-danger {
      background-color: rgba(239, 68, 68, 0.15);
      color: #ef4444;
      color:red;
    } */

    /* .icon {
      width: 26px;
      height: 26px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 4px;
      color: white;
      font-size: 12px;
      font-weight: bold;
    }

    .motor-icon {
      background-color: #6366f1;
    } */

    /* .platform-icon {
      background-color: #475569;
    } */

    .flow-value {
      font-size: 10px;
      font-weight: 600;
      color: var(--slate-700);
      margin-bottom: 2px;
    }

    .flow-label {
      font-size: 12px;
      color: #475569;
    }

    .flow-inactive {
      color: var(--slate-400);
    }

    @media (max-width: 500px) {
      body {
        padding: 1rem;
      }

      .dashboard-header {
        padding: 1rem;
      }

      .dashboard-content {
        padding: 1rem;
      }

      .details-row {
        gap: 15px;
      }

      /* .grid-layout {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        padding: 15px;
      } */
    }

    @media (max-width: 425px) {
      .grid-layout {
        grid-template-columns: repeat(2, 1fr);
        /* still 2 columns */
      }
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.5s ease;
    }

    .grid-2-cols {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }

    .section-subtitle {
      font-size: 1.05rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      /* color: #4b5563; */
      margin-bottom: 0;
    }

    .metric-row {
      /* background-color: #f8fafc; */
      border-radius: 12px;
      padding: 1rem 1.4rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }

    /* Custom hover effects for each metric type */
    .metric-row.voltage:hover {
      background-color: rgba(245, 158, 11, 0.1);
      border-left: 4px solid #f59e0b;
    }

    .metric-row.current:hover {
      background-color: rgba(239, 68, 68, 0.1);
      border-left: 4px solid #ef4444;
    }

    .metric-row.energy:hover {
      background-color: rgba(16, 185, 129, 0.1);
      border-left: 4px solid #10b981;
    }

    .metric-row.frequency:hover {
      background-color: rgba(6, 182, 212, 0.1);
      border-left: 4px solid #06b6d4;
    }

    .metric-row.speed:hover {
      background-color: rgba(59, 130, 246, 0.1);
      border-left: 4px solid #3b82f6;
    }

    .metric-row.hours:hover {
      background-color: rgba(107, 114, 128, 0.1);
      border-left: 4px solid #6b7280;
    }

    .small-card-value {
      font-weight: 600;
      font-size: 1.2rem;
      /* color: #1f2937; */
      /* background: linear-gradient(90deg, #f8fafc, white); */
      padding: 0.4rem 1rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      min-width: 100px;
      text-align: center;
    }

    .metric-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 42px;
      height: 42px;
      border-radius: 10px;
      margin-right: 12px;
      color: white;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .icon-voltage {
      background-color: #f59e0b;
      animation: pulse 2s infinite;
    }

    .icon-current {
      background-color: #ef4444;
    }

    .icon-energy {
      background-color: #10b981;
    }

    .icon-frequency {
      background-color: #06b6d4;
    }

    .icon-speed {
      background-color: #3b82f6;
    }

    .icon-hours {
      background-color: #6b7280;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .grid-2-cols {
        grid-template-columns: 1fr;
      }
    }
  </style>
  <title>Dashboard</title>
  <?php
  include(BASE_PATH . "assets/html/start-page.php");
  ?>
  <div class="d-flex flex-column flex-shrink-0 p-3 main-content ">
    <div class="container-fluid">
      <div class="row mb-1 mt-1">
        <div class="col-12 ">
          <p class="breadcrumb-text text-muted m-0">
            <i class="bi bi-house-door-fill "></i> Pages / <span class="fw-medium ">Dashboard</span>
          </p>
        </div>
      </div>
      <div class="row mb-2">
      <div class="col-12 d-flex justify-content-end">
        <p class="m-0" id="update_time">
          <span class="text-body-tertiary">Updated On: </span>
          <span id="auto_update_date_time"></span>
        </p>
      </div>
    </div>
      <div class="row">
        <div class="container">
          <!-- First Row -->
          <section class="section">
            <div class="grid">
              <!-- Operation Mode -->
              <div class="card">
                <div class="card-header purple-gradient">
                  <h3 class="text-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="3"></circle>
                      <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    Operation Mode
                  </h3>
                </div>
                <div class="card-body p-0">
                  <div class="center-content purple-light-bg relative">
                    <div class="bg-grid-pattern absolute inset-0 opacity-5"></div>
                    <div class="relative z-10">
                      <h3 id="operation-mode-display" class="operation-mode">
                        <!-- Auto -->
                        <span class="pulse-dot"></span>
                      </h3>
                      <p id="operation-mode-subtitle" class="operation-mode-subtitle">
                        <!-- Automatic Operation -->
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Input Pressure -->
              <div class="card">
                <div class="card-header blue-gradient">
                  <h3 class="text-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path d="M16.2 7.8l-2 6.3-6.4 2.1 2-6.3z"></path>
                    </svg>
                    Inlet Pressure / Level
                  </h3>
                </div>
                <div class="card-body p-0 ">
                  <div class="center-content blue-light-bg ">
                    <span id="inlet-pressure-status" class="m-4 badge-outline-success fw-bold">
                      Yes
                      <span class="pulse-dot"></span>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Output Pressure -->
              <div class="card">
                <div class="card-header cyan-gradient">
                  <h3 class="text-light">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <path d="M16.2 7.8l-2 6.3-6.4 2.1 2-6.3z"></path>
                    </svg>
                    Outlet Pressure
                  </h3>
                </div>
                <div class="card-body cyan-light-bg">
                  <div class="data-row">
                    <span class="data-label data-label-color">Pressure 1:</span>
                    <span id="outlet-pressure-1" class="data-value data-label-color">0 kg/m²</span>
                  </div>
                  <div class="data-row">
                    <span class="data-label data-label-color">Pressure 2:</span>
                    <span id="outlet-pressure-2" class="data-value data-label-color">0 kg/m²</span>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <div class="dashboard-container">
            <div class="dashboard-content">
              <!-- Motor Details and Platform Details in a single row -->
              <div class="details-row">
                <!-- Motor Details -->
                <div class="details-column details-column-color card">
                  <div class="details-title section-title"> <i class="bi bi-cpu-fill"></i> Motor Details</div>
                  <div class="grid-layout">
                    <div class="grid-item grid-item-color ">
                      <div class="item-title">
                        <div>Motor 1</div>

                      </div>
                      <div class="item-status">
                        <div id="motor-1-status" class="badge-success"><i class="bi bi-power"></i> ON</div>
                      </div>
                      <div id="motor-1-flow" class="flow-value">Flow Rate: 0 L/min</div>
                      <div class="item-details">Running from Last : <span id="motor-1-runtime" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Motor 2</div>
                      </div>
                      <div class="item-status">
                        <span id="motor-2-status" class="badge-danger"><i class="bi bi-power"></i> OFF</span>
                      </div>
                      <div id="motor-2-flow" class="flow-value flow-inactive">Flow Rate: 0.00 L/min</div>
                      <div class="item-details">Running from Last : <span id="motor-2-runtime" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Motor 3</div>
                      </div>
                      <div class="item-status">
                        <span id="motor-3-status" class="badge-danger"><i class="bi bi-power"></i> OFF</span>
                      </div>
                      <div id="motor-3-flow" class="flow-value flow-inactive">Flow Rate: 0.00 L/min</div>
                      <div class="item-details">Running from Last : <span id="motor-3-runtime" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Motor 4</div>
                      </div>
                      <div class="item-status">
                        <span id="motor-4-status" class="badge-danger"><i class="bi bi-power"></i> OFF</span>
                      </div>
                      <div id="motor-4-flow" class="flow-value flow-inactive">Flow Rate: 0.00 L/min</div>
                      <div class="item-details">Running from Last : <span id="motor-4-runtime" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Motor 5</div>
                      </div>
                      <div class="item-status">
                        <span id="motor-5-status" class="badge-danger"><i class="bi bi-power"></i> OFF</span>
                      </div>
                      <div id="motor-5-flow" class="flow-value flow-inactive">Flow Rate: 0.00 L/min</div>
                      <div class="item-details">Running from Last : <span id="motor-5-runtime" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Motor 6</div>
                      </div>
                      <div class="item-status">
                        <span id="motor-6-status" class="badge-danger"><i class="bi bi-power"></i> OFF</span>
                      </div>
                      <div id="motor-6-flow" class="flow-value flow-inactive">Flow Rate: 0.00 L/min</div>
                      <div class="item-details">Running from Last : <span id="motor-6-runtime" class="fw-bold">0 min</span></div>
                    </div>
                  </div>
                </div>

                <!-- Platform Details -->
                <div class="details-column details-column-color card">
                  <div class="details-title section-title"> <i class="bi bi-stack"></i> Platforms Valve Details</div>
                  <div class="grid-layout">

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Platform 1 & 2</div>
                      </div>
                      <div class="item-status">
                        <span id="platform-1-2-status" class="badge-success">NA</span>
                      </div>
                      <div class="item-details mt-2">Open from Last : <span id="platform-1-2-time" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Platform 3 & 4</div>
                      </div>
                      <div class="item-status">
                        <span id="platform-3-4-status" class="badge-success">NA</span>
                      </div>
                      <div class="item-details mt-2">Open from Last : <span id="platform-3-4-time" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Platform 5 & 6</div>
                      </div>
                      <div class="item-status">
                        <span id="platform-5-6-status" class="badge-success">NA</span>
                      </div>
                      <div class="item-details mt-2">Open from Last : <span id="platform-5-6-time" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Platform 7</div>
                      </div>
                      <div class="item-status">
                        <span id="platform-7-status" class="badge-success">NA</span>
                      </div>
                      <div class="item-details mt-2">Open from Last : <span id="platform-7-time" class="fw-bold">0 min</span></div>
                    </div>

                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Platform 8</div>
                      </div>
                      <div class="item-status">
                        <span id="platform-8-status" class="badge-success">NA</span>
                      </div>
                      <div class="item-details mt-2">Open from Last : <span id="platform-8-time" class="fw-bold">0 min</span></div>
                    </div>


                    <div class="grid-item grid-item-color">
                      <div class="item-title">
                        <div>Platform 9 & 10</div>
                      </div>
                      <div class="item-status">
                        <span id="platform-9-10-status" class="badge-success">NA</span>
                      </div>
                      <div class="item-details mt-2">Open from Last : <span id="platform-9-10-time" class="fw-bold">0 min</span></div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Electrical Details Section -->
          <section id="electrical-section" class="section">
            <div class="details-title">Electrical Details</div>

            <div class="tabs">
              <div class="tabs-list mt-2">
                <div class="tab-trigger active" data-tab="motor_1">Motor 1</div>
                <div class="tab-trigger" data-tab="motor_2">Motor 2</div>
                <div class="tab-trigger" data-tab="motor_3">Motor 3</div>
                <div class="tab-trigger" data-tab="motor_4">Motor 4</div>
                <div class="tab-trigger" data-tab="motor_5">Motor 5</div>
                <div class="tab-trigger" data-tab="motor_6">Motor 6</div>
              </div>

              <!-- Motor 1 Electrical Details -->

              <div id="motor_1" class="tab-content active">
                <div class="card">
                  <div class="card-header amber-gradient">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-cpu-fill fs-4"></i> Motor 1 Electrical Details
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="grid-2-cols">
                      <!-- Left Side -->
                      <div>
                        <!-- Line Voltage -->
                        <div class="metric-row voltage">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-voltage">
                              <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Line Voltage</h5>
                          </div>
                          <span id="motor-1-voltage" class="small-card-value">0 V</span>
                        </div>

                        <!-- Motor Current -->
                        <div class="metric-row current">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-current">
                              <i class="bi bi-plug-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Motor Current</h5>
                          </div>
                          <span id="motor-1-current" class="small-card-value">0 A</span>
                        </div>

                        <!-- Energy (kWh) -->
                        <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-half fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kWh)</h5>
                          </div>
                          <span id="motor-1-kwh" class="small-card-value">0</span>
                        </div>

                        <!-- Energy (kVAh) -->
                        <!-- <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-full fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kVAh)</h5>
                          </div>
                          <span id="motor-1-kvah" class="small-card-value">0</span>
                        </div> -->
                      </div>

                      <!-- Right Side -->
                      <div>
                        <!-- Frequency -->
                        <div class="metric-row frequency">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-frequency">
                              <i class="bi bi-activity fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Frequency</h5>
                          </div>
                          <span id="motor-1-frequency" class="small-card-value">0 Hz</span>
                        </div>

                        <!-- Speed -->
                        <div class="metric-row speed">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-speed">
                              <i class="bi bi-speedometer2 fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Speed</h5>
                          </div>
                          <span id="motor-1-speed" class="small-card-value">0 RPM</span>
                        </div>

                        <!-- Running Hours -->
                        <div class="metric-row hours">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-hours">
                              <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Running Hours</h5>
                          </div>
                          <span id="motor-1-hours" class="small-card-value">0 hrs</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div id="motor_2" class="tab-content">
                <div class="card">
                  <div class="card-header amber-gradient">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-cpu-fill fs-4"></i> Motor 2 Electrical Details
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="grid-2-cols">
                      <!-- Left Side -->
                      <div>
                        <!-- Line Voltage -->
                        <div class="metric-row voltage">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-voltage">
                              <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Line Voltage</h5>
                          </div>
                          <span id="motor-2-voltage" class="small-card-value">0 V</span>
                        </div>

                        <!-- Motor Current -->
                        <div class="metric-row current">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-current">
                              <i class="bi bi-plug-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Motor Current</h5>
                          </div>
                          <span id="motor-2-current" class="small-card-value">0 A</span>
                        </div>

                        <!-- Energy (kWh) -->
                        <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-half fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kWh)</h5>
                          </div>
                          <span id="motor-2-kwh" class="small-card-value">0</span>
                        </div>

                        <!-- Energy (kVAh) -->
                        <!-- <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-full fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kVAh)</h5>
                          </div>
                          <span id="motor-2-kvah" class="small-card-value">0</span>
                        </div> -->
                      </div>

                      <!-- Right Side -->
                      <div>
                        <!-- Frequency -->
                        <div class="metric-row frequency">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-frequency">
                              <i class="bi bi-activity fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Frequency</h5>
                          </div>
                          <span id="motor-2-frequency"
                            class="small-card-value">0</span>
                        </div>

                        <!-- Speed -->
                        <div class="metric-row speed">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-speed">
                              <i class="bi bi-speedometer2 fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Speed</h5>
                          </div>
                          <span id="motor-2-speed" class="small-card-value">0 RPM</span>
                        </div>

                        <!-- Running Hours -->
                        <div class="metric-row hours">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-hours">
                              <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Running Hours</h5>
                          </div>
                          <span id="motor-2-hours" class="small-card-value">0 hrs</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Similar tab content for motors 3-6 would go here -->
              <div id="motor_3" class="tab-content">
                <div class="card">
                  <div class="card-header amber-gradient">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-cpu-fill fs-4"></i> Motor 3 Electrical Details
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="grid-2-cols">
                      <!-- Left Side -->
                      <div>
                        <!-- Line Voltage -->
                        <div class="metric-row voltage">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-voltage">
                              <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Line Voltage</h5>
                          </div>
                          <span id="motor-3-voltage" class="small-card-value">0 V</span>
                        </div>

                        <!-- Motor Current -->
                        <div class="metric-row current">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-current">
                              <i class="bi bi-plug-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Motor Current</h5>
                          </div>
                          <span id="motor-3-current" class="small-card-value">0 A</span>
                        </div>

                        <!-- Energy (kWh) -->
                        <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-half fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kWh)</h5>
                          </div>
                          <span id="motor-3-kwh" class="small-card-value">0</span>
                        </div>

                        <!-- Energy (kVAh) -->
                        <!-- <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-full fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kVAh)</h5>
                          </div>
                          <span id="motor-3-kvah" class="small-card-value">0</span>
                        </div> -->
                      </div>

                      <!-- Right Side -->
                      <div>
                        <!-- Frequency -->
                        <div class="metric-row frequency">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-frequency">
                              <i class="bi bi-activity fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Frequency</h5>
                          </div>
                          <span id="motor-3-frequency" class="small-card-value">0 Hz</span>
                        </div>

                        <!-- Speed -->
                        <div class="metric-row speed">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-speed">
                              <i class="bi bi-speedometer2 fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Speed</h5>
                          </div>
                          <span id="motor-3-speed" class="small-card-value">0 RPM</span>
                        </div>

                        <!-- Running Hours -->
                        <div class="metric-row hours">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-hours">
                              <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Running Hours</h5>
                          </div>
                          <span id="motor-3-hours" class="small-card-value">0 hrs</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="motor_4" class="tab-content">
                <div class="card">
                  <div class="card-header amber-gradient">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-cpu-fill fs-4"></i> Motor 4 Electrical Details
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="grid-2-cols">
                      <!-- Left Side -->
                      <div>
                        <!-- Line Voltage -->
                        <div class="metric-row voltage">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-voltage">
                              <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Line Voltage</h5>
                          </div>
                          <span id="motor-4-voltage" class="small-card-value">0 V</span>
                        </div>

                        <!-- Motor Current -->
                        <div class="metric-row current">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-current">
                              <i class="bi bi-plug-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Motor Current</h5>
                          </div>
                          <span id="motor-4-current" class="small-card-value">0 A</span>
                        </div>

                        <!-- Energy (kWh) -->
                        <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-half fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kWh)</h5>
                          </div>
                          <span id="motor-4-kwh" class="small-card-value">0</span>
                        </div>

                        <!-- Energy (kVAh) -->
                        <!-- <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-full fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kVAh)</h5>
                          </div>
                          <span id="motor-4-kvah" class="small-card-value">0</span>
                        </div> -->
                      </div>

                      <!-- Right Side -->
                      <div>
                        <!-- Frequency -->
                        <div class="metric-row frequency">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-frequency">
                              <i class="bi bi-activity fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Frequency</h5>
                          </div>
                          <span id="motor-4-frequency" class="small-card-value">0 Hz</span>
                        </div>

                        <!-- Speed -->
                        <div class="metric-row speed">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-speed">
                              <i class="bi bi-speedometer2 fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Speed</h5>
                          </div>
                          <span id="motor-4-speed" class="small-card-value">0 RPM</span>
                        </div>

                        <!-- Running Hours -->
                        <div class="metric-row hours">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-hours">
                              <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Running Hours</h5>
                          </div>
                          <span id="motor-4-hours" class="small-card-value">0 hrs</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div id="motor_5" class="tab-content">
                <div class="card">
                  <div class="card-header amber-gradient">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-cpu-fill fs-4"></i> Motor 5 Electrical Details
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="grid-2-cols">
                      <!-- Left Side -->
                      <div>
                        <!-- Line Voltage -->
                        <div class="metric-row voltage">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-voltage">
                              <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Line Voltage</h5>
                          </div>
                          <span id="motor-5-voltage" class="small-card-value">0 V</span>
                        </div>

                        <!-- Motor Current -->
                        <div class="metric-row current">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-current">
                              <i class="bi bi-plug-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Motor Current</h5>
                          </div>
                          <span id="motor-5-current" class="small-card-value">0 A</span>
                        </div>

                        <!-- Energy (kWh) -->
                        <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-half fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kWh)</h5>
                          </div>
                          <span id="motor-5-kwh" class="small-card-value">0</span>
                        </div>

                        <!-- Energy (kVAh) -->
                        <!-- <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-full fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kVAh)</h5>
                          </div>
                          <span id="motor-5-kvah" class="small-card-value">0</span>
                        </div> -->
                      </div>

                      <!-- Right Side -->
                      <div>
                        <!-- Frequency -->
                        <div class="metric-row frequency">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-frequency">
                              <i class="bi bi-activity fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Frequency</h5>
                          </div>
                          <span id="motor-5-frequency" class="small-card-value">0 Hz</span>
                        </div>

                        <!-- Speed -->
                        <div class="metric-row speed">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-speed">
                              <i class="bi bi-speedometer2 fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Speed</h5>
                          </div>
                          <span id="motor-5-speed" class="small-card-value">0 RPM</span>
                        </div>

                        <!-- Running Hours -->
                        <div class="metric-row hours">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-hours">
                              <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Running Hours</h5>
                          </div>
                          <span id="motor-5-hours" class="small-card-value">0 hrs</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div id="motor_6" class="tab-content">
                <div class="card">
                  <div class="card-header amber-gradient">
                    <h3 class="mb-0 d-flex align-items-center gap-2">
                      <i class="bi bi-cpu-fill fs-4"></i> Motor 6 Electrical Details
                    </h3>
                  </div>
                  <div class="card-body">
                    <div class="grid-2-cols">
                      <!-- Left Side -->
                      <div>
                        <!-- Line Voltage -->
                        <div class="metric-row voltage">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-voltage">
                              <i class="bi bi-lightning-charge-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Line Voltage</h5>
                          </div>
                          <span id="motor-6-voltage" class="small-card-value">0 V</span>
                        </div>

                        <!-- Motor Current -->
                        <div class="metric-row current">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-current">
                              <i class="bi bi-plug-fill fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Motor Current</h5>
                          </div>
                          <span id="motor-6-current" class="small-card-value">0 A</span>
                        </div>

                        <!-- Energy (kWh) -->
                        <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-half fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kWh)</h5>
                          </div>
                          <span id="motor-6-kwh" class="small-card-value">0</span>
                        </div>

                        <!-- Energy (kVAh) -->
                        <!-- <div class="metric-row energy">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-energy">
                              <i class="bi bi-battery-full fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Energy (kVAh)</h5>
                          </div>
                          <span id="motor-6-kvah" class="small-card-value">0</span>
                        </div> -->
                      </div>

                      <!-- Right Side -->
                      <div>
                        <!-- Frequency -->
                        <div class="metric-row frequency">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-frequency">
                              <i class="bi bi-activity fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Frequency</h5>
                          </div>
                          <span id="motor-6-frequency" class="small-card-value">0 Hz</span>
                        </div>

                        <!-- Speed -->
                        <div class="metric-row speed">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-speed">
                              <i class="bi bi-speedometer2 fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Speed</h5>
                          </div>
                          <span id="motor-6-speed" class="small-card-value">0 RPM</span>
                        </div>

                        <!-- Running Hours -->
                        <div class="metric-row hours">
                          <div class="d-flex align-items-center">
                            <div class="metric-icon icon-hours">
                              <i class="bi bi-clock-history fs-5"></i>
                            </div>
                            <h5 class="section-subtitle">Running Hours</h5>
                          </div>
                          <span id="motor-6-hours" class="small-card-value">0 hrs</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </section>
        </div>




      </div>
    </div>
  </div>


  </main>
  <script src="<?php echo BASE_PATH; ?>assets/js/sidebar-menu.js"></script>
  <script src="<?php echo BASE_PATH; ?>assets/js/project/dashboard1.js"></script>

  <?php
  include(BASE_PATH . "assets/html/body-end.php");
  include(BASE_PATH . "assets/html/html-end.php");
  ?>


