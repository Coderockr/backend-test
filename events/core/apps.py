from django.apps import AppConfig


class CoreConfig(AppConfig):
    name = "events.core"

    def ready(self):
        import events.core.signals.email  # noqa
