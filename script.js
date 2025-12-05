// camera capture helper
async function initCamera(videoEl) {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({video:true});
    videoEl.srcObject = stream;
    return stream;
  } catch (e) {
    console.error('camera init failed', e);
    throw e;
  }
}
