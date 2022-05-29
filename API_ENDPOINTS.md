# API ENDPOINTs

## APP Registration

| ENDPOINT   | METHOD | INPUT                                     | DESCRIPTION                    | Response                                        |
| ---------- | ------ | ----------------------------------------- | ------------------------------ | ----------------------------------------------- |
| `api/apps` | POST   | [Register app input](#register-app-input) | Registers an app to the system | [Register app response](#register-app-response) |

## Events

| ENDPOINT     | METHOD | INPUT                                   | DESCRIPTION                          | Response                                      |
| ------------ | ------ | --------------------------------------- | ------------------------------------ | --------------------------------------------- |
| `api/events` | POST   | [Store event input](#store-event-input) | Stores an event/action to the system | [Store event response](#store-event-response) |

## Attendees

| ENDPOINT                   | METHOD | INPUT                                         | DESCRIPTION                      | Response                                            |
| -------------------------- | ------ | --------------------------------------------- | -------------------------------- | --------------------------------------------------- |
| `api/attendees`            | POST   | [Store attendee input](#store-attendee-input) | Stores an attendee to the system | [Store attendee response](#store-attendee-response) |
| `api/attendees/{attendee}` | GET    | N/A                                           | Fetch a single attendee          |                                                     |

## Shows

| ENDPOINT           | METHOD | INPUT | DESCRIPTION                 |
| ------------------ | ------ | ----- | --------------------------- |
| `api/shows`        | GET    | N/A   | Fetch a collection of shows |
| `api/shows/{show}` | GET    | N/A   | Fetch a single show         |

## Client Shows

| ENDPOINT                        | METHOD | INPUT | DESCRIPTION                |
| ------------------------------- | ------ | ----- | -------------------------- |
| `clients/{client}/shows`        | GET    | N/A   | Fetch client shows         |
| `clients/{client}/shows/{show}` | GET    | N/A   | Fetch a single client show |

## Register App Input

```json
{
    "name": ["string", "required"],
    "client_id": ["int", "required", "exists:clients,id"],
    "show_id": ["int", "required", "exists:shows,id"],
    "kiosk_id": ["string"],
    "machine_id": ["string"]
}
```

## Store Event Input

```json
{
    "app_id": ["int", "required", "exists:apps,id"],
    "show_id": ["int", "required", "exists:shows,id"],
    "attendee_id": ["int"],
    "action_code": ["int", "required"],
    "event_code": ["int", "required"]
}
```

## Store Attendee Input

```json
{
    "badge_id": ["string", "required"],
    "first_name": ["string", "required"],
    "last_name": ["string", "required"],
    "email": ["string", "required"]
}
```

# Responses For Some Endpoints

## Register App Response

```json
{
    "data": {
        "object_type": "App",
        "id": 1,
        "name": "Merck Recruitment Panels",
        "client_id": 1,
        "show_id": 1,
        "kiosk_id": "Back Wall, Left",
        "machine_id": "351cx-132c234-xwaeu2-sxl2"
    }
}
```

## Store Event Response

```json
{
    "data": {
        "object_type": "Event",
        "id": 1,
        "name": "Merck Recruitment Panels",
        "client_id": 1,
        "show_id": 1,
        "kiosk_id": "Back Wall, Left",
        "machine_id": "351cx-132c234-xwaeu2-sxl2"
    }
}
```

## Store Attendee Response

```json
{
    "data": {
        "object_type": "Attendee",
        "id": 1,
        "badge_id": "391d3ee0-2f46-3d12-97ec-d7a7475391a2",
        "first_name": "Maynard",
        "last_name": "Carter",
        "job_title": "Excavating Machine Operator",
        "email": "darron.crona@shields.com",
        "phone": "1-445-450-6973",
        "address_line_1": "3427 Jana Island",
        "address_line_2": "57296 Beau Drive",
        "country": "Libyan Arab Jamahiriya",
        "city": "Hartmannbury",
        "state": "Jeffberg",
        "postal_code": "07913-6443",
        "meta": "[]",
        "notes": "Dicta doloribus totam laborum quod voluptas. Unde ducimus dolor nulla distinctio. Qui ut tempora itaque nulla sunt autem."
    }
}
```
