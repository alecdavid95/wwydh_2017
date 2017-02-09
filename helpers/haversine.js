// copied verbatem from stack overflow, not using currently

function haversine_distance(coords1, coords2) {
    var dLat = toRad(coords2.latitude - coords1.latitude);
    var dLon = toRad(coords2.longitude - coords1.longitude)

    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(toRad(coords1.latitude)) *
          Math.cos(toRad(coords2.latitude)) *
          Math.sin(dLon / 2) * Math.sin(dLon / 2);

    return 12742 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function toRad(x) {
    return x * Math.PI / 180;
}
