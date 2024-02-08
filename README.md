## TP Final docker avancé

Cloner le repo :

```bash
git clone https://github.com/BDanielo/finalDocker.git
```

# Docker file

Build l'image

```bash
docker build -t symfony-tpfinal .
```

# Docker Compose

Lors du premier lancement du docker compose faire un build

```bash
docker compose build
```

Puis lancer le docker compose

```bash
docker compose up
```

Si la bdd ne s'initialize pas relancer faire un down puis un up (ça ne devrais pas le faire normalement)

# Docker compose swarm

Lancer le swarm

```bash
docker swarm init
```

Deploy la stack

```bash
docker stack deploy -c docker-compose-swarm.yml test-symfo
```

