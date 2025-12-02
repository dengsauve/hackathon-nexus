from django.contrib.auth.models import AbstractUser
from django.db import models

# Create your models here.
class User(AbstractUser):
    display_name = models.CharField(max_length=100, blank=True)
    organization = models.CharField(max_length=150, blank=True)
    bio = models.TextField(blank=True)
    role = models.CharField(
        max_length=50,
        choices=[
            ('participant', 'Participant'),
            ('judge', 'Judge'),
            ('organizer', 'Organizer'),
        ],
        default='participant',
    )

    def __str__(self):
        return self.display_name or self.username