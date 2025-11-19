// preloader.js
// Vanilla JS preloader that mirrors the behavior of your React PulsePreloader
// Exports: startLoadingAnimation(), stopLoadingAnimation(), showPreloader(), hidePreloader()

let percent = 0;
let _loading = false;
let _intervalId = null;
let _fadeTimeoutId = null;

const getEls = () => ({
  preloader: document.getElementById("preloader"),
  pulse: document.getElementById("pulse"),
  percentEl: document.getElementById("percent"),
});

/**
 * Start the percent + pulse animation.
 * Safe to call multiple times (it will reset).
 */
export function startLoadingAnimation() {
  const { preloader, pulse, percentEl } = getEls();

  // safety: if elements missing, do nothing but still set _loading true
  _loading = true;

  if (!preloader || !pulse || !percentEl) {
    console.warn(
      "preloader elements missing (expected IDs: preloader, pulse, percent)."
    );
    return;
  }

  // ensure preloader is visible
  preloader.style.display = "";
  pulse.classList.remove("fade");
  percentEl.classList.remove("fade");

  // reset state
  percent = 0;
  percentEl.textContent = "0%";

  // clear existing interval if any
  if (_intervalId) {
    clearInterval(_intervalId);
    _intervalId = null;
  }
  if (_fadeTimeoutId) {
    clearTimeout(_fadeTimeoutId);
    _fadeTimeoutId = null;
  }

  // start incrementing
  _intervalId = setInterval(() => {
    if (!_loading) {
      clearInterval(_intervalId);
      _intervalId = null;
      return;
    }

    percent++;
    if (percent > 100) percent = 100;
    percentEl.textContent = percent + "%";

    if (percent >= 100) {
      // trigger fade classes
      pulse.classList.add("fade");
      percentEl.classList.add("fade");

      // after fade duration, either reset percent to 0 (if still loading)
      // or keep at 100 waiting for stopLoadingAnimation to hide the preloader.
      _fadeTimeoutId = setTimeout(() => {
        _fadeTimeoutId = null;
        if (_loading) {
          pulse.classList.remove("fade");
          percentEl.classList.remove("fade");
          percent = 0;
          percentEl.textContent = "0%";
        }
      }, 400); // match your CSS fade length
    }
  }, 30); // same pace as your React example
}

/**
 * Stop the loading animation, trigger final fade, and hide preloader.
 * Returns a Promise that resolves after the fade completes so callers can safely show main.
 */
export function stopLoadingAnimation({ fadeMs = 400 } = {}) {
  const { preloader, pulse, percentEl } = getEls();

  return new Promise((resolve) => {
    // mark not loading so interval will clear itself quickly
    _loading = false;

    // clear timers
    if (_intervalId) {
      clearInterval(_intervalId);
      _intervalId = null;
    }
    if (_fadeTimeoutId) {
      clearTimeout(_fadeTimeoutId);
      _fadeTimeoutId = null;
    }

    if (!preloader || !pulse || !percentEl) {
      // resolve immediately if elements missing
      resolve();
      return;
    }

    // ensure final state shows 100% then fade
    percent = 100;
    percentEl.textContent = "100%";

    // add fade classes for final fade
    pulse.classList.add("fade");
    percentEl.classList.add("fade");

    // hide the preloader after fade duration
    setTimeout(() => {
      preloader.style.display = "none";
      // reset classes so next startLoadingAnimation() starts clean
      pulse.classList.remove("fade");
      percentEl.classList.remove("fade");
      percent = 0;
      percentEl.textContent = "0%";
      resolve();
    }, fadeMs);
  });
}

/**
 * Utility to immediately show preloader element (no animation)
 */
export function showPreloader() {
  const { preloader } = getEls();
  if (preloader) preloader.style.display = "";
}

/**
 * Utility to immediately hide preloader (no animation)
 */
export function hidePreloader() {
  const { preloader, pulse, percentEl } = getEls();
  if (!preloader) return;
  preloader.style.display = "none";
  if (pulse) pulse.classList.remove("fade");
  if (percentEl) {
    percentEl.classList.remove("fade");
    percentEl.textContent = "0%";
  }
  _loading = false;
  if (_intervalId) {
    clearInterval(_intervalId);
    _intervalId = null;
  }
  if (_fadeTimeoutId) {
    clearTimeout(_fadeTimeoutId);
    _fadeTimeoutId = null;
  }
}
