# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.require_version ">= 1.7.0"

Vagrant.configure("2") do |config|

    provisioner = Vagrant::Util::Platform.windows? ? "ansible_local" : "ansible"
    hostsFile = Vagrant::Util::Platform.windows? ? "ansible/hosts/hosts_windows.txt" : "ansible/hosts/hosts_linux.txt"

    config.vm.box = "ubuntu/xenial64"

    config.vm.network "private_network", ip: "10.0.11.44"
    config.ssh.forward_agent = true
    config.vm.boot_timeout = 600

    if provisioner == "ansible"

    	config.vm.synced_folder "/var/www/gravity-float", "/var/www/gravity-float"

    end

    if provisioner == "ansible_local"

    	config.vm.synced_folder ".", "/vagrant"
        config.vm.synced_folder ".", "/var/www/gravity-float"

    end

    config.vm.provider :virtualbox do |v|

        v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        v.customize ["modifyvm", :id, "--memory", 2048]
        v.customize ["modifyvm", :id, "--cpus", 2]

    end

    config.vm.provision provisioner do |ansible|
        ansible.verbose         = "v"
        ansible.playbook        = "ansible/site.yml"
        ansible.limit           = "all"
        ansible.inventory_path  = hostsFile
    end
end
