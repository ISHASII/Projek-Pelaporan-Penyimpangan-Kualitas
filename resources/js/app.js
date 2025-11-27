import "./bootstrap";

// Import flatpickr and make it available globally for legacy scripts that expect window.flatpickr
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// expose to window so existing inline scripts can detect and bind
window.flatpickr = flatpickr;

// Import Chart.js and make it available globally
import Chart from "chart.js/auto";
window.Chart = Chart;
