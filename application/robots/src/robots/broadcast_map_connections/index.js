const io = require("socket.io-client");

const CONFIG = require("../../config/config");
const generalHelper = require("../../helpers/general");

const {
	getConnectionsByFloor,
	getUniqueFloors
} = require("../../models/api_connection_map");

const socket = io(
	CONFIG.integrations.automatic_notifications.sockets_host +
		":" +
		CONFIG.integrations.automatic_notifications.sockets_port,
	{
		upgrade: false,
		transports: ["websocket"],
		rejectUnauthorized: false,
		query: { source: "cosmos_worker", name: "maps_connections" }
	}
);

socket.on("connect", function () {
	generalHelper.showDetails("Worker Broadcast Connections : Socket connected");
});

socket.on("error", function (err) {
	generalHelper.showDetails("Worker Broadcast Connections : Socket error", err);
});

socket.on("disconnect", function (err) {
	generalHelper.showDetails(
		"Worker Broadcast Connections : Socket disconnected",
		err
	);
});

async function wakeUpWorkers() {
	try {
		let floorIds = await getUniqueFloors();

		floorIds.forEach(function (floorId) {
			generalHelper.showDetails(
				`Wake Up Workers : Starting worker (${floorId})`
			);
			startConnectionsCollection(floorId);
		});
	} catch (err) {
		generalHelper.showDetails("Wake Up Workers : Error getting floors");
		process.exit(1);
	}
}

async function collectConnectionsByFloor(floorId) {
	try {
		let connections = await getConnectionsByFloor(floorId);

		socket.emit("message_user_by", {
			skip_log: true,
			pattern: "floor",
			target: floorId,
			packet_type: "g",
			data: { connections }
		});

		startConnectionsCollection(floorId);
	} catch (err) {
		generalHelper.showDetails(
			`Collect Connections By Floor : Floor : ${floorId}, Error : ${err}`
		);
		startConnectionsCollection(floorId);
	}
}

function startConnectionsCollection(floorId) {
	setTimeout(function () {
		collectConnectionsByFloor(floorId);
	}, CONFIG.integrations.map_connections.interval_get_connections);
}

wakeUpWorkers();
