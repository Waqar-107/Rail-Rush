/**
 * Created by waqar on 11/5/2017.
 */

var id = "complaint id: 107";
var text = "complaint: In the late summer of that year we lived in a house in a village that looked across the river and the plain to the mountains. In the bed of the river there were pebbles and boulders, dry and white in the sun, and the water was clear and swiftly moving and blue in the channels. Troops went by the house and down the road and the dust they raised powdered the leaves of the trees. The trunks of the trees too were dusty and the leaves fell early that year and we saw the troops marching along the road and the dust rising and leaves, stirred by the breeze, falling and the soldiers marching and afterward the road bare and white except for the leaves.";

function updateId() {
    document.getElementById("complaint_id").innerHTML = id;
}

function updateMessage() {

    document.getElementById("message").innerHTML = text;
}