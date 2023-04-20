function copy(url) {
    navigator.clipboard.writeText(url);
    console.log("Copied text: " + url);
  }