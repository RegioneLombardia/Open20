// function loadImage(img) {
//   var imgShimmer = img.parentNode;

//   imgShimmer.className += imgShimmer.className ? ' loaded-shimmer' : 'loaded-shimmer';
// };

jQuery('img.shimmer-image').on('load', function() {
  $('.img-shimmer').addClass('loaded-image');
});



 