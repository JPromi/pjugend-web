function shareEvent(eventID, domain) {
    if (navigator.share) {
        navigator.share({

            title: 'Veranstaltung teilen',

            url: 'https://' + domain + '/events/view?id=' + eventID
        })
    } else {
        alert("Dein Browser unterstützt dieses Feature nicht");
    }
}