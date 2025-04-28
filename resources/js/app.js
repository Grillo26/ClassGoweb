import './bootstrap';
import PusherPushNotifications from "@pusher/push-notifications-web";

PusherPushNotifications.init({
    instanceId: "your-instance-id"
})
    .then(beamsClient => {
        return beamsClient.start()
            .then(() => beamsClient.addDeviceInterest("meet-notifications"))
            .then(() => console.log("Push Notifications enabled!"))
            .catch(console.error);
    });
